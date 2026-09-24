<?php

namespace App\Filament\Resources\Identity\InviteRequests\Tables;

use App\Models\Identity\InviteRequest;
use App\Services\Identity\InviteRequestApproveService;
use App\Services\Identity\InviteRequestRegenerateLinkService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InviteRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        InviteRequest::STATUS_PENDING => 'Pendente',
                        InviteRequest::STATUS_APPROVED => 'Aprovado',
                        InviteRequest::STATUS_REJECTED => 'Rejeitado',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        InviteRequest::STATUS_PENDING => 'warning',
                        InviteRequest::STATUS_APPROVED => 'success',
                        InviteRequest::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Solicitado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        InviteRequest::STATUS_PENDING => 'Pendente',
                        InviteRequest::STATUS_APPROVED => 'Aprovado',
                        InviteRequest::STATUS_REJECTED => 'Rejeitado',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Aprovar')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->visible(fn (InviteRequest $record): bool => $record->status === InviteRequest::STATUS_PENDING)
                    ->requiresConfirmation()
                    ->modalDescription('Isso cria a conta do anfitrião e gera um link para ele definir a senha.')
                    ->action(function (InviteRequest $record, InviteRequestApproveService $service): void {
                        $link = $service->execute($record);

                        Notification::make()
                            ->title('Convite aprovado')
                            ->body("Envie este link para {$record->name} definir a senha: {$link}")
                            ->success()
                            ->persistent()
                            ->actions([
                                Action::make('copy')
                                    ->label('Copiar link')
                                    ->button()
                                    ->alpineClickHandler("window.navigator.clipboard.writeText('{$link}')"),
                            ])
                            ->send();
                    }),
                Action::make('regenerateLink')
                    ->label('Gerar novo link')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('gray')
                    ->visible(fn (InviteRequest $record): bool => $record->status === InviteRequest::STATUS_APPROVED)
                    ->action(function (InviteRequest $record, InviteRequestRegenerateLinkService $service): void {
                        $link = $service->execute($record);

                        Notification::make()
                            ->title('Novo link gerado')
                            ->body("Envie este link para {$record->name} definir a senha: {$link}")
                            ->success()
                            ->persistent()
                            ->actions([
                                Action::make('copy')
                                    ->label('Copiar link')
                                    ->button()
                                    ->alpineClickHandler("window.navigator.clipboard.writeText('{$link}')"),
                            ])
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Rejeitar')
                    ->icon(Heroicon::OutlinedXMark)
                    ->color('danger')
                    ->visible(fn (InviteRequest $record): bool => $record->status === InviteRequest::STATUS_PENDING)
                    ->requiresConfirmation()
                    ->action(fn (InviteRequest $record) => $record->forceFill(['status' => InviteRequest::STATUS_REJECTED])->save()),
            ]);
    }
}
