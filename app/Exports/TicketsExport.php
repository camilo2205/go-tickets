<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Ticket;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnWidths, WithStyles, WithMapping
{
    protected $cliente_id;

    protected $estado;

    protected $fechas;

    protected $tags_id;

    public function __construct($cliente_id, $estado, $fechas, $tags_id)
    {
        $this->cliente_id = $cliente_id;
        $this->estado = $estado;
        $this->fechas = $fechas;
        $this->tags_id = $tags_id;
    }

    public function headings(): array
    {
        return [
            'CLIENTE',
            'DESCRIPCIÓN',
            'ESTADO',
            'NIVEL SLA',
            'ENCARGADO',
            'FECHA',
            'FECHA ATENCION',
            'FECHA RESOLUCIÓN',
            'TAGS'
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $cliente = Cliente::where('user_id', auth()->user()->id)->first();
        $funcionario = Funcionario::where('user_id', auth()->user()->id)->first();
        $tags_id = $this->tags_id;
        $consulta = Ticket::query();

        if (isset($this->fechas[1])) {
            $consulta->whereDate('tickets.created_at', '>=', $this->fechas[0])
                ->whereDate('tickets.created_at', '<=', $this->fechas[1]);
        }
        if ($this->estado) {
            $consulta->where('estado', $this->estado);
        }
        if ($this->cliente_id) {
            $consulta->where('cliente_id', $this->cliente_id);
        }
        if ($tags_id) {
            $consulta->whereHas('tags', function ($query) use ($tags_id) {
                $query->whereIn('tag_id', $tags_id);
            });
        }

        if ($cliente) {
            $tickets = $consulta->where('cliente_id', $cliente->id)->orderBy('tickets.id', 'desc')->get();
        } elseif ($funcionario) {
            $tickets = $consulta->where('funcionario_id', $funcionario->id)
                ->orWhereNull('funcionario_id')
                ->orderBy('tickets.id', 'desc')->get();
        } else {
            $tickets = $consulta->orderBy('tickets.id', 'desc')->get();
        }

        return $tickets;
    }

    public function map($ticket): array
    {
        $tagsName = '';
        foreach ($ticket->tags as $first_loop  => $tags) {
            if ($first_loop == count($ticket->tags) - 1) {
                $tagsName .= $tags->nombre . '.';
            } else {
                $tagsName .= $tags->nombre . ', ';
            }
        }
        $razon_social = $ticket->cliente ? $ticket->cliente->razon_social : '';
        return [
            $razon_social,
            $ticket->descripcion,
            $ticket->estado,
            $ticket->nivel_sla ? ucfirst(str_replace('_', ' ', $ticket->nivel_sla)) : '',
            $ticket->funcionario ? $ticket->funcionario->user->name : '',
            Date::parse($ticket->created_at),
            $ticket->respuestas()->first() ? $ticket->respuestas()->first()->created_at : '',
            $ticket->respuestas()->where('cerrar', 1)->first() ? $ticket->respuestas()->where('cerrar', 1)->first()->created_at : '',
            $tagsName,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 45,
            'D' => 18,
            'E' => 30
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            "C:H"  => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            "A:H"  => ['alignment' => ['vertical' => Alignment::VERTICAL_CENTER]]
        ];
    }
}
