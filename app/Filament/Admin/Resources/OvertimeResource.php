<?php

namespace App\Filament\Admin\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Overtime;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\OvertimeResource\Pages;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use App\Filament\Admin\Resources\OvertimeResource\RelationManagers;
use Illuminate\Support\Facades\Auth;

class OvertimeResource extends Resource
{
    protected static ?string $model = Overtime::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Overtime';

    protected static ?string $pluralModelLabel = 'Overtime';

    protected static ?string $slug = 'overtime';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('user_id')
                            ->label('Pekerja')
                            ->relationship('users', 'name')
                            ->searchable()
                            ->required(),
                        TextInput::make('posisi')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('pekerjaan')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('tanggal')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->required(),
                        TimePicker::make('jam_mulai')
                            ->native(false)
                            ->seconds(false)
                            ->closeOnDateSelection()
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::calculateTotalJam($get, $set);
                            })
                            ->required(),
                        TimePicker::make('jam_selesai')
                            ->native(false)
                            ->seconds(false)
                            ->closeOnDateSelection()
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::calculateTotalJam($get, $set);
                            })
                            ->required(),
                        TextInput::make('total_jam')
                            ->required(),
                    ])
                    ->columns(2),
                Card::make()
                    ->schema([
                        SignaturePad::make('ttd_pekerja')
                            ->disabled(Auth::user()->hasRole(['manager', 'Manager']))->downloadable()
                            ->filename('ttd_pekerja')
                            ->backgroundColor('rgba(255, 255, 255, 0)')
                            ->backgroundColorOnDark('#fff')
                            ->exportBackgroundColor('#fff')
                            ->penColor('#000')
                            ->penColorOnDark('#000')
                            ->exportPenColor('#000'),
                        SignaturePad::make('ttd_manager')
                            ->disabled(Auth::user()->hasRole(['pegawai', 'Pegawai']))
                            ->downloadable()
                            ->filename('ttd_manager')
                            ->backgroundColor('rgba(255, 255, 255, 0)')
                            ->backgroundColorOnDark('#fff')
                            ->exportBackgroundColor('#fff')
                            ->penColor('#000')
                            ->penColorOnDark('#000')
                            ->exportPenColor('#000'),
                    ])
                    ->columns(2),
            ]);
    }

    private static function calculateTotalJam(Forms\Get $get, Forms\Set $set)
    {
        $jamMulai = $get('jam_mulai');
        $jamSelesai = $get('jam_selesai');

        if ($jamMulai && $jamSelesai) {
            $start = Carbon::parse($jamMulai);
            $end = Carbon::parse($jamSelesai);

            if ($end->lt($start)) {
                $end->addDay();
            }

            $totalJam = $end->diffInSeconds($start);
            $decimalHours = $totalJam / 3600;

            $set('total_jam', abs($decimalHours) . ' jam');
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('users.name')
                    ->label('Pekerja')
                    ->sortable(),
                TextColumn::make('posisi')
                    ->searchable(),
                TextColumn::make('pekerjaan')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('jam_mulai'),
                TextColumn::make('jam_selesai'),
                TextColumn::make('total_jam'),
                TextColumn::make('ttd_pekerja')
                    ->formatStateUsing(fn($state) => $state ? "<img src='{$state}' alt='Tanda Tangan pekerja'>" : 'Tidak ada tanda tangan')
                    ->html(),
                TextColumn::make('ttd_manager')
                    ->formatStateUsing(fn($state) => $state ? "<img src='{$state}' alt='Tanda Tangan manager'>" : 'Tidak ada tanda tangan')
                    ->html(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOvertimes::route('/'),
            'create' => Pages\CreateOvertime::route('/create'),
            'edit' => Pages\EditOvertime::route('/{record}/edit'),
        ];
    }
}
