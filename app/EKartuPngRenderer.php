<?php

namespace App;

use App\Models\Anggota;
use App\Models\EKartuAnggota;
use Illuminate\Support\Str;

class EKartuPngRenderer
{
    private const WIDTH = 1200;

    private const HEIGHT = 700;

    /**
     * @var array<string, list<string>>
     */
    private const FONT = [
        'A' => ['01110', '10001', '10001', '11111', '10001', '10001', '10001'],
        'B' => ['11110', '10001', '10001', '11110', '10001', '10001', '11110'],
        'C' => ['01111', '10000', '10000', '10000', '10000', '10000', '01111'],
        'D' => ['11110', '10001', '10001', '10001', '10001', '10001', '11110'],
        'E' => ['11111', '10000', '10000', '11110', '10000', '10000', '11111'],
        'F' => ['11111', '10000', '10000', '11110', '10000', '10000', '10000'],
        'G' => ['01111', '10000', '10000', '10111', '10001', '10001', '01111'],
        'H' => ['10001', '10001', '10001', '11111', '10001', '10001', '10001'],
        'I' => ['11111', '00100', '00100', '00100', '00100', '00100', '11111'],
        'J' => ['00111', '00010', '00010', '00010', '10010', '10010', '01100'],
        'K' => ['10001', '10010', '10100', '11000', '10100', '10010', '10001'],
        'L' => ['10000', '10000', '10000', '10000', '10000', '10000', '11111'],
        'M' => ['10001', '11011', '10101', '10101', '10001', '10001', '10001'],
        'N' => ['10001', '11001', '10101', '10011', '10001', '10001', '10001'],
        'O' => ['01110', '10001', '10001', '10001', '10001', '10001', '01110'],
        'P' => ['11110', '10001', '10001', '11110', '10000', '10000', '10000'],
        'Q' => ['01110', '10001', '10001', '10001', '10101', '10010', '01101'],
        'R' => ['11110', '10001', '10001', '11110', '10100', '10010', '10001'],
        'S' => ['01111', '10000', '10000', '01110', '00001', '00001', '11110'],
        'T' => ['11111', '00100', '00100', '00100', '00100', '00100', '00100'],
        'U' => ['10001', '10001', '10001', '10001', '10001', '10001', '01110'],
        'V' => ['10001', '10001', '10001', '10001', '10001', '01010', '00100'],
        'W' => ['10001', '10001', '10001', '10101', '10101', '10101', '01010'],
        'X' => ['10001', '10001', '01010', '00100', '01010', '10001', '10001'],
        'Y' => ['10001', '10001', '01010', '00100', '00100', '00100', '00100'],
        'Z' => ['11111', '00001', '00010', '00100', '01000', '10000', '11111'],
        '0' => ['01110', '10001', '10011', '10101', '11001', '10001', '01110'],
        '1' => ['00100', '01100', '00100', '00100', '00100', '00100', '01110'],
        '2' => ['01110', '10001', '00001', '00010', '00100', '01000', '11111'],
        '3' => ['11110', '00001', '00001', '01110', '00001', '00001', '11110'],
        '4' => ['00010', '00110', '01010', '10010', '11111', '00010', '00010'],
        '5' => ['11111', '10000', '10000', '11110', '00001', '00001', '11110'],
        '6' => ['01110', '10000', '10000', '11110', '10001', '10001', '01110'],
        '7' => ['11111', '00001', '00010', '00100', '01000', '01000', '01000'],
        '8' => ['01110', '10001', '10001', '01110', '10001', '10001', '01110'],
        '9' => ['01110', '10001', '10001', '01111', '00001', '00001', '01110'],
        '-' => ['00000', '00000', '00000', '11111', '00000', '00000', '00000'],
        '/' => ['00001', '00010', '00010', '00100', '01000', '01000', '10000'],
        ':' => ['00000', '00100', '00100', '00000', '00100', '00100', '00000'],
        '.' => ['00000', '00000', '00000', '00000', '00000', '00110', '00110'],
        ' ' => ['00000', '00000', '00000', '00000', '00000', '00000', '00000'],
    ];

    /**
     * @var list<string>
     */
    private array $rows = [];

    public function render(Anggota $anggota, EKartuAnggota $eKartu): string
    {
        $this->rows = array_fill(0, self::HEIGHT, str_repeat($this->rgb(15, 23, 42), self::WIDTH));

        $this->rectangle(0, 0, 24, self::HEIGHT, [79, 70, 229]);
        $this->rectangle(760, 70, 360, 560, [30, 41, 70]);
        $this->rectangle(800, 110, 280, 280, [255, 255, 255]);

        $this->text('SIPADI', 70, 70, 8, [165, 180, 252]);
        $this->text('KARTU ANGGOTA PERPUSTAKAAN', 70, 155, 4, [255, 255, 255]);
        $this->text('NAMA', 70, 270, 4, [165, 180, 252]);
        $this->text(Str::limit($anggota->nama_lengkap, 22, ''), 70, 315, 5, [255, 255, 255]);
        $this->text('NOMOR KARTU / NIK', 70, 410, 4, [165, 180, 252]);
        $this->text($eKartu->no_anggota, 70, 455, 7, [255, 255, 255]);
        $this->text('BERLAKU SAMPAI', 70, 555, 4, [165, 180, 252]);
        $this->text($eKartu->masa_berlaku?->format('d-m-Y') ?? '-', 70, 600, 5, [255, 255, 255]);

        $this->identifierPattern($eKartu->barcode ?? $eKartu->no_anggota, 825, 135, 230);
        $this->text($eKartu->kalangan ?? 'UMUM', 815, 450, 5, [255, 255, 255]);
        $this->text('KODE KARTU', 815, 510, 3, [165, 180, 252]);
        $this->text(Str::limit($eKartu->barcode ?? '-', 24, ''), 815, 550, 2, [255, 255, 255]);

        return $this->png();
    }

    /**
     * @param  array{int, int, int}  $color
     */
    private function text(string $text, int $x, int $y, int $scale, array $color): void
    {
        $cursor = $x;
        $normalized = Str::upper(Str::ascii($text));

        foreach (mb_str_split($normalized) as $character) {
            $glyph = self::FONT[$character] ?? self::FONT[' '];

            foreach ($glyph as $row => $pattern) {
                foreach (str_split($pattern) as $column => $pixel) {
                    if ($pixel === '1') {
                        $this->rectangle(
                            $cursor + ($column * $scale),
                            $y + ($row * $scale),
                            $scale,
                            $scale,
                            $color
                        );
                    }
                }
            }

            $cursor += 6 * $scale;

            if ($cursor >= self::WIDTH - 30) {
                break;
            }
        }
    }

    /**
     * @param  array{int, int, int}  $color
     */
    private function rectangle(int $x, int $y, int $width, int $height, array $color): void
    {
        $x = max(0, $x);
        $y = max(0, $y);
        $width = min($width, self::WIDTH - $x);
        $height = min($height, self::HEIGHT - $y);
        $pixels = str_repeat($this->rgb(...$color), $width);

        for ($row = $y; $row < $y + $height; $row++) {
            $this->rows[$row] = substr_replace($this->rows[$row], $pixels, $x * 3, $width * 3);
        }
    }

    private function identifierPattern(string $value, int $x, int $y, int $size): void
    {
        $modules = 21;
        $moduleSize = intdiv($size, $modules);
        $bits = '';

        foreach (str_split(hash('sha256', $value)) as $hex) {
            $bits .= str_pad(base_convert($hex, 16, 2), 4, '0', STR_PAD_LEFT);
        }

        for ($row = 0; $row < $modules; $row++) {
            for ($column = 0; $column < $modules; $column++) {
                $index = (($row * $modules) + $column) % strlen($bits);

                if ($bits[$index] === '1') {
                    $this->rectangle(
                        $x + ($column * $moduleSize),
                        $y + ($row * $moduleSize),
                        $moduleSize,
                        $moduleSize,
                        [15, 23, 42]
                    );
                }
            }
        }
    }

    private function png(): string
    {
        $raw = '';

        foreach ($this->rows as $row) {
            $raw .= "\x00".$row;
        }

        return "\x89PNG\r\n\x1a\n"
            .$this->chunk('IHDR', pack('NNCCCCC', self::WIDTH, self::HEIGHT, 8, 2, 0, 0, 0))
            .$this->chunk('IDAT', gzcompress($raw, 9))
            .$this->chunk('IEND', '');
    }

    private function chunk(string $type, string $data): string
    {
        return pack('N', strlen($data)).$type.$data.pack('N', crc32($type.$data));
    }

    private function rgb(int $red, int $green, int $blue): string
    {
        return chr($red).chr($green).chr($blue);
    }
}
