<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use App\Services\OpenLibraryService;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('author')->required()->maxLength(255),
                TextInput::make('isbn')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->hintAction(
                        Action::make('lookupIsbn')
                            ->label('Cari via ISBN')
                            ->icon('heroicon-m-magnifying-glass')
                            ->action(function (OpenLibraryService $openLibrary, Get $get, Set $set) {
                                $isbn = strtoupper((string) preg_replace('/[^0-9Xx]/', '', (string) $get('isbn')));

                                if ($isbn === '') {
                                    Notification::make()
                                        ->warning()
                                        ->title('ISBN belum diisi.')
                                        ->send();

                                    return;
                                }

                                try {
                                    $data = $openLibrary->findByIsbn($isbn);
                                } catch (\Throwable $e) {
                                    report($e);

                                    Notification::make()
                                        ->danger()
                                        ->title('Gagal menghubungi Open Library.')
                                        ->body('Periksa koneksi internet lalu coba lagi.')
                                        ->send();

                                    return;
                                }

                                if (! $data) {
                                    Notification::make()
                                        ->warning()
                                        ->title('Data buku tidak ditemukan.')
                                        ->body("Tidak ada hasil untuk ISBN {$isbn}.")
                                        ->send();

                                    return;
                                }

                                $set('title', '');
                                $set('author', '');
                                $set('description', '');
                                $set('cover_url', []);

                                if (filled($data['title'] ?? null)) {
                                    $set('title', $data['title']);
                                }

                                if (filled($data['author'] ?? null)) {
                                    $set('author', $data['author']);
                                }

                                if (filled($data['description'] ?? null)) {
                                    $set('description', $data['description']);
                                }

                                if (filled($data['cover_url'] ?? null)) {
                                    $set('cover_url', [$data['cover_url']]);
                                }

                                Notification::make()
                                    ->success()
                                    ->title('Data buku ditemukan dari Open Library.')
                                    ->send();
                            }),
                    ),
                Textarea::make('description'),
                TextInput::make('total_copies')->numeric()->default(1)->required(),
                TextInput::make('available_copies')->numeric()->default(1)->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                FileUpload::make('file_url')
                    ->directory('books')
                    ->acceptedFileTypes(['application/pdf', 'application/epub+zip'])
                    ->disk('local')
                    ->nullable(),
                FileUpload::make('cover_url')
                    ->directory('covers')
                    ->image()
                    ->disk('public')
                    ->nullable()
                    ->fetchFileInformation(false)
                    ->getUploadedFileUsing(function (FileUpload $component, string $file): array {
                        return [
                            'name' => basename($file),
                            'size' => 0,
                            'type' => null,
                            'url' => filter_var($file, FILTER_VALIDATE_URL)
                                ? $file
                                : Storage::disk('public')->url($file),
                        ];
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_url')->circular(),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('author')->searchable(),
                TextColumn::make('isbn')->searchable(),
                TextColumn::make('category.name')->searchable(),
                TextColumn::make('available_copies')->sortable(),
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}