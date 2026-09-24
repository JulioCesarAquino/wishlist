<?php

namespace App\Filament\Resources\Events\Events\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Anfitrião')
                    ->relationship('user', 'name')
                    ->required()
                    ->default(fn () => auth()->id())
                    ->disabled(fn () => ! auth()->user()?->isAdmin())
                    ->dehydrated(),
                Select::make('type')
                    ->label('Tipo de evento')
                    ->options([
                        'casamento' => 'Casamento',
                        'cha_bebe' => 'Chá de bebê',
                        'cha_panela' => 'Chá de panela',
                        'aniversario' => 'Aniversário',
                        'outro' => 'Outro',
                    ])
                    ->required(),
                TextInput::make('title')
                    ->label('Título')
                    ->required(),
                TextInput::make('slug')
                    ->helperText('Deixe em branco para gerar automaticamente a partir do título. Usado na URL pública do evento.')
                    ->unique(ignoreRecord: true),
                DatePicker::make('event_date')
                    ->label('Data do evento'),
                FileUpload::make('cover_image')
                    ->label('Imagem de capa')
                    ->image()
                    ->disk('public')
                    ->imageEditor()
                    ->imagePreviewHeight('160')
                    ->directory('events/covers'),
                FileUpload::make('gallery')
                    ->label('Galeria de fotos')
                    ->image()
                    ->disk('public')
                    ->multiple()
                    ->reorderable()
                    ->imagePreviewHeight('120')
                    ->directory('events/gallery'),
                Textarea::make('description')
                    ->label('Texto do evento')
                    ->columnSpanFull(),
                Textarea::make('story')
                    ->label('Nossa história')
                    ->helperText('Texto livre para contar a história do casal/evento. Aparece na seção "Nossa história" da página pública.')
                    ->rows(5)
                    ->columnSpanFull(),
                Section::make('Aparência da página pública')
                    ->columns(2)
                    ->components([
                        Select::make('font_family')
                            ->label('Fonte')
                            ->options([
                                'default' => 'Moderna',
                                'script' => 'Manuscrita',
                                'serif' => 'Elegante',
                                'classic' => 'Clássica',
                            ])
                            ->default('default')
                            ->native(false)
                            ->columnSpanFull(),
                        ColorPicker::make('primary_color')
                            ->label('Cor primária')
                            ->helperText('Tom de fundo geral da página.'),
                        ColorPicker::make('secondary_color')
                            ->label('Cor secundária')
                            ->helperText('Usada nos botões e detalhes decorativos.'),
                        ColorPicker::make('font_color_primary')
                            ->label('Cor da fonte principal')
                            ->helperText('Usada no nome do casal e títulos.'),
                        ColorPicker::make('font_color_secondary')
                            ->label('Cor da fonte secundária')
                            ->helperText('Usada nos textos e menu.'),
                    ]),
                Section::make('Mercado Pago')
                    ->description('Necessário para o evento poder receber pagamentos via Pix, cartão ou boleto.')
                    ->components([
                        TextInput::make('mp_access_token')
                            ->label('Access Token')
                            ->password()
                            ->revealable()
                            ->hintAction(
                                Action::make('mpCredentialsHelp')
                                    ->label('Como obter minhas credenciais')
                                    ->icon(Heroicon::OutlinedQuestionMarkCircle)
                                    ->url('https://www.mercadopago.com.br/developers/pt/docs/linx/additional-content/your-integrations/credentials#bookmark_obter_credenciais')
                                    ->openUrlInNewTab(),
                            )
                            ->columnSpanFull(),
                        TextInput::make('mp_public_key')
                            ->label('Public Key')
                            ->columnSpanFull(),
                    ]),
                Toggle::make('is_published')
                    ->label('Publicado')
                    ->helperText('Enquanto desativado, só você e o admin conseguem ver a página pública (modo prévia). Convidados não têm acesso.')
                    ->default(false),
                Toggle::make('is_premium')
                    ->label('Recurso premium: ver quem deu cada presente')
                    ->helperText('Sem isso, o anfitrião só vê o total arrecadado na aba de Pedidos, sem os nomes dos convidados.')
                    ->visible(fn (): bool => (bool) auth()->user()?->isAdmin())
                    ->default(false),
            ]);
    }
}
