<table class='table table-bordered table-sm table-striped table-hover'>
    <tbody>
    <tr>
        <th>{{__('ProviderId')}}</th>
        <th>{{__('Nombre')}}</th>
        <th>{{__('Username')}}</th>
        <th>{{__('Total jugado')}}</th>
        <th>{{__('Total premiado')}}</th>
        <th>{{__('Apuesta total')}}</th>
        <th>{{__('Beneficio total')}}</th>
        <th>{{__('rtp')}}</th>
    </tr>
    @foreach($items as $item)
    <tr>
        <td>{{$item->provider_id}}</td>
        <td>{{$item->name}}</td>
        <td class="init_agent">{{$item->username}}</td>
        <td>{{$item->total_played}}</td>
        <td>{{$item->total_won}}</td>
        <td>{{$item->total_bet}}</td>
        <td>{{$item->total_profit}}</td>
        <td>{{$item->rtp}}</td>
    </tr>
    @endforeach
    {{--<tr>
        <td class='text-center' colspan='36'><br></td>
        <td class='text-center'><br></td>
    </tr>
    <tr>
        <td class='text-center' colspan='35' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
        <td class='text-center' colspan='2' style='background-color: #81d0f6;'><strong>Ganancia total</strong></td>
    </tr>
    <tr>
        <td class='text-center' colspan='35' style='border: 1px solid #ffffff;background-color: rgb(255,255,255);'></td>
        <td class='text-center' colspan='2' style='background-color: #81d0f6;'><strong>0.00</strong></td>
    </tr>--}}
    </tbody>
</table>