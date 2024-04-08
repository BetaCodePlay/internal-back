<?php

namespace App\Altenar\Collections;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Class AltenarTicketsCollection
 *
 * This class allows formatting Altenar tickets
 *
 * @package App\Altenar\Collections
 * @author  Miguel Sira
 */
class AltenarTicketsCollection
{

    /**
     * Format all tickets
     *
     * @param array $tickets Tickets data
     */
    public function formatAll(array $tickets)
    {
        foreach ($tickets as $ticket) {
            $timezone = session('timezone');
            $ticket->date = $ticket->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $ticket->amount = number_format($ticket->amount, 2);
            $ticket->status = $this->getStatus($ticket->status);;
        }
    }

    /**
     * Format ticket details
     *
     * @param object $ticket Ticket data
     * @param object $ticketData Ticket data
     * @param bool $reference Ticket data
     */
    public function ticketDetails(object $ticket, object $ticketData, bool $reference)
    {
        $timezone = session('timezone');
        $ticket->user = sprintf(
            '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
            route('users.details', $ticketData->user_id),
            $ticketData->user_id
        );
        $ticket->username = $ticketData->username;
        $ticket->status = $ticket->BetStatus;
        $ticket->date = $ticketData->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
        $ticket->amount = number_format($ticketData->amount, 2);
        $ticket->win = number_format($ticket->Winnings, 2);
        $ticket->provider_transaction = $ticketData->reference;
        if ($reference){
            $ticket->provider_transaction = $ticketData->reference;
        } else {
            $ticket->provider_transaction = $ticketData->provider_transaction;
        }
    }

    /**
     * Format ticket selections
     *
     * @param object $ticket Ticket data
     * @return array
     */
    public function ticketSelections(object $ticket): array
    {
        $selections = [];

        if($ticket->EventCount > 1) {
            foreach ($ticket->EventList->ExtEvent as $selection) {
                $status = (isset($selection->Markets)) ? $selection->Markets->ExtMarket->Status : $selection->ExtMarket->Status;
                $selectionObject = new \stdClass();
                $selectionObject->event = (isset($selection->EventName)) ? $selection->EventName : $ticket->EventList->ExtEvent->EventName;
                $selectionObject->bet_type = (isset($selection->Markets)) ? $selection->Markets->ExtMarket->Name : $selection->ExtMarket->Name;
                $selectionObject->selection = (isset($selection->Markets)) ? $selection->Markets->ExtMarket->Outcome : $selection->ExtMarket->Outcome;
                $selectionObject->quota = (isset($selection->Markets)) ? $selection->Markets->ExtMarket->Price : $selection->ExtMarket->Price;
                $selectionObject->status = $status;
                $selections[] = $selectionObject;
            }
        } else {
            $status = $ticket->EventList->ExtEvent->Markets->ExtMarket->Status;
            $selectionObject = new \stdClass();
            $selectionObject->event = $ticket->EventList->ExtEvent->EventName;
            $selectionObject->bet_type = $ticket->EventList->ExtEvent->Markets->ExtMarket->Name;
            $selectionObject->selection = $ticket->EventList->ExtEvent->Markets->ExtMarket->Outcome;
            $selectionObject->quota = $ticket->EventList->ExtEvent->Markets->ExtMarket->Price;
            $selectionObject->status = $status;
            $selections[] = $selectionObject;
        }

        return $selections;
    }

    /**
     * Format ticket
     *
     * @param object $ticket Ticket data
     * @return string
     */
    public function formatTicket(object $ticket): string
    {
        $status = $ticket->BetStatus;
        $html = '';
        $html .= '<table class="table">';

        $html .= '<tr>';
        $html .= sprintf(
            '<td><strong>%s</strong>: %s</td>',
            _i('Amount'),
            number_format($ticket->TotalStake, 2)
        );

        $html .= sprintf(
            '<td><strong>%s</strong>: %s</td>',
            _i('Win'),
            number_format($ticket->Winnings, 2)
        );

        $html .= sprintf(
            '<td colspan="3"><strong>%s</strong>: %s</td>',
            _i('Status'),
            $status
        );
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= sprintf(
            '<td colspan="5"><h5>%s</h5></td>',
            _i('Selections')
        );
        $html .= '</tr>';

        foreach ($ticket->EventList->ExtEvent as $selection) {
            $status = $selection->Markets->ExtMarket->Status;
            $html .= '<tr>';
            $html .= sprintf(
                '<td><strong>%s</strong>: %s</td>',
                _i('Event'),
                $selection->EventName
            );

            $html .= sprintf(
                '<td><strong>%s</strong>: %s</td>',
                _i('Bet type'),
                $selection->Markets->ExtMarket->Name
            );

            $html .= sprintf(
                '<td><strong>%s</strong>: %s</td>',
                _i('Selection'),
                $selection->Markets->ExtMarket->Outcome
            );

            $html .= sprintf(
                '<td><strong>%s</strong>: %s</td>',
                _i('Quota'),
                $selection->Markets->ExtMarket->Price
            );

            $html .= sprintf(
                '<td><strong>%s</strong>: %s</td>',
                _i('Status'),
                $status
            );

            $html .= sprintf(
                '<td><strong>%s</strong>: %s</td>',
                _i('Sport'),
                $selection->SportID
            );

            $html .= sprintf(
                '<td><strong>%s</strong>: %s</td>',
                _i('League'),
                $selection->ChampionshipId
            );
            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }

    /**
     * Get status
     *
     * @param int $status Status ID
     * @return string
     */
    private function getStatus(int $status): string
    {
        switch ($status) {
            case 1:
            {
                $status = _i('Pending');
                break;
            }
            case 2:
            {
                $status = _i('Won');
                break;
            }
            case 3:
            {
                $status = _i('Lost');
                break;
            }
            case 4:
            {
                $status = _i('Cancelled');
                break;
            }
            case 5:
            {
                $status = _i('Fully cashouted');
                break;
            }
            case 6:
            {
                $status = _i('Returned');
                break;
            }
            case 7:
            {
                $status = _i('Not accepted');
                break;
            }
            case 8:
            {
                $status = _i('Partialy cashouted');
                break;
            }
            case 9:
            {
                $status = _i('Waiting');
                break;
            }
        }
        return $status;
    }

    /**
     * Format totals
     *
     * @param array $tickets Altenar data
     * @return array
     */
    public function formatTotals(array $tickets): array
    {
        $ticketsData = [];
        $timezone = session('timezone');

        foreach ($tickets as $ticket) {
            $ticketObject = new \stdClass();
            $ticketObject->user = $ticket->user_id;
            $ticketObject->username = $ticket->username;
            $ticketObject->date = Carbon::createFromFormat('Y-m-d H:i:s', $ticket->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $ticketObject->provider_transaction = $ticket->provider_transaction;
            $ticketObject->status = $this->getStatus($ticket->data->Status);
            $ticketObject->amounts = sprintf(
                '<strong>%s:</strong> %s<br><strong>%s:</strong> %s',
                _i('Amount'),
                number_format($ticket->amount, 2),
                _i('Win'),
                number_format($ticket->amount, 2)
            );
            $html = '';

            foreach ($ticket->data->BetSelections as $selection) {
                $status = $this->getStatus($selection->Status);
                $html .= '<ul>';
                $html .= sprintf(
                    '<li><strong>%s</strong>: %s</li>',
                    _i('Event'),
                    $selection->UnitName
                );

                $html .= sprintf(
                    '<li><strong>%s</strong>: %s</li>',
                    _i('Bet type'),
                    $selection->MarketName
                );

                $html .= sprintf(
                    '<li><strong>%s</strong>: %s</li>',
                    _i('Selection'),
                    $selection->SelectionName
                );

                $html .= sprintf(
                    '<li><strong>%s</strong>: %s</li>',
                    _i('Quota'),
                    $selection->Coefficient
                );

                $html .= sprintf(
                    '<li><strong>%s</strong>: %s</li>',
                    _i('Status'),
                    $status
                );

                $html .= sprintf(
                    '<li><strong>%s</strong>: %s</li>',
                    _i('Sport'),
                    $selection->SportName
                );

                $html .= sprintf(
                    '<li><strong>%s</strong>: %s</li>',
                    _i('League'),
                    $selection->CompetitionName
                );

                $html .= '</ul>';
            }
            $ticketObject->details = $html;
            $ticketsData[] = $ticketObject;
        }
        return $ticketsData;
    }

}
