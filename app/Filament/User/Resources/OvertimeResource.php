<?php

namespace App\Filament\User\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Overtime;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\User\Resources\OvertimeResource\Pages;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use App\Filament\User\Resources\OvertimeResource\RelationManagers;

class OvertimeResource extends Resource
{
    protected static ?string $model = Overtime::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Overtime';

    protected static ?string $pluralModelLabel = 'Overtime';

    protected static ?string $slug = 'overtime';

    public static function roleAdminManager(): bool
    {
        return !Auth::user()->hasRole(['ADMIN', 'admin', 'super_admin', 'MANAGER', 'manager', 'Manager']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('user_id')
                            ->label('Pekerja')
                            ->options(
                                User::role(['pegawai', 'Pegawai'])->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->disabled(self::roleAdminManager()),
                        TextInput::make('posisi')
                            ->required()
                            ->maxLength(255)
                            ->disabled(self::roleAdminManager()),
                        TextInput::make('pekerjaan')
                            ->required()
                            ->maxLength(255)
                            ->disabled(self::roleAdminManager()),
                        DatePicker::make('tanggal')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->displayFormat('d-M-Y')
                            ->required()
                            ->disabled(self::roleAdminManager()),
                        TimePicker::make('jam_mulai')
                            ->native(false)
                            ->seconds(false)
                            ->closeOnDateSelection()
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::calculateTotalJam($get, $set);
                            })
                            ->required()
                            ->disabled(self::roleAdminManager()),
                        TimePicker::make('jam_selesai')
                            ->native(false)
                            ->seconds(false)
                            ->closeOnDateSelection()
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::calculateTotalJam($get, $set);
                            })
                            ->required()
                            ->disabled(self::roleAdminManager()),
                        TextInput::make('total_jam')
                            ->required()
                            ->disabled(self::roleAdminManager()),
                    ])
                    ->columns(2),
                Card::make()
                    ->schema([
                        SignaturePad::make('ttd_pekerja')
                            ->downloadable()
                            ->filename('ttd_pekerja')
                            ->backgroundColor('rgba(255, 255, 255, 0)')
                            ->backgroundColorOnDark('#fff')
                            ->exportBackgroundColor('#fff')
                            ->penColor('#000')
                            ->penColorOnDark('#000')
                            ->exportPenColor('#000'),
                        SignaturePad::make('ttd_manager')
                            ->disabled(self::roleAdminManager())
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
                    ->dateTime('d-M-Y')
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
                    ->dateTime('d-M-Y H:i:s')

            ])
            ->query(function () {
                return Overtime::query()->with('users')->where('user_id', Auth::user()->id);
            })
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
