@if($action == 'new')
  @include('rates.create')
@elseif($action == 'edit')
  @include('rates.edit')
@else
<div class="col-md-12 text-center">
  <label for="">Template name:</label>
  <select id="rate-template-name" class="form-control" style="width: 300px;display:inline-block;">
    @foreach($branch->rates()->get() as $template)
    <option value="{{ $template->tmplate_id }}" {{ $rate->tmplate_id == $template->tmplate_id ? "selected" : "" }}
      data-href="{{ route('branchs.rates.index', [$branch, 'tmplate_id' => $template->tmplate_id]) }}"
      >{{ $template->tmplate_name }}</option>
    @endforeach
  </select>
  @if($rate)
  <label for="">Color:</label>
  <div class="color-picker" style="background: {{ $rate->Color }};pointer-events: none;"></div>
  @endif
  <button class="btn btn-danger cancel-selection" style="float:right;margin-top:5px;display:none;">Cancel Selection</button>
  <hr style="margin: 10px 0px 0px 0px;">
  
</div>

<div class="col-md-4">
  <h4>Miscellaneous</h4>
  <div class="row">
    <div class="control-radio col-xs-6">
      <input type="radio" disabled="true" {{ $rate->charge_mode == 1 ? "checked" : ""}}>
      <label>Per Min</label>
    </div>
    <div class="control-radio col-xs-6">
      <input type="radio" disabled="true" {{ $rate->charge_mode == 2 ? "checked" : ""}}>
      <label >Per 5 Mins</label>
    </div>
  </div>
  <hr style="margin: 10px 0px;">
  <div class="form-group">
    <div class="row">
      <div class="col-xs-6">
        <div class="control-checkbox" style="margin-top: 5px;">
          <input type="checkbox" disabled="true" {{ $rate->MinimumChrg ? "checked" : ""}}>
          <label>Change Minimum </label>
        </div>
      </div>
      <div class="col-xs-6">
        <input type="number" class="form-control" style="width: 100px;display:inline-block;"
          disabled="true" value="{{ $rate->MinimumTime }}"> (mins)
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="row">
      <div class="col-xs-6">
        <label for="">Timezone 1:</label>
        <input type="time" class="form-control" disabled="true" value="{{ $rate->ZoneStart1 }}">
      </div>
      <div class="col-xs-6">
        <label for="">Discount 1: (%)</label>
        <input type="number" class="form-control" disabled="true" value="{{ $rate->Discount1 }}">
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="row">
      <div class="col-xs-6">
        <label for="">Timezone 2:</label>
        <input type="time" class="form-control" disabled="true" value="{{ $rate->ZoneStart2 }}">
      </div>
      <div class="col-xs-6">
        <label for="">Discount 2: (%)</label>
        <input type="number" class="form-control" disabled="true" value="{{ $rate->Discount2 }}">
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="row">
      <div class="col-xs-6">
        <label for="">Timezone 3:</label>
        <input type="time" class="form-control" disabled="true" value="{{ $rate->ZoneStart3 }}">
      </div>
      <div class="col-xs-6">
        <label for="">Discount 3: (%)</label>
        <input type="number" class="form-control" disabled="true" value="{{ $rate->Discount3 }}">
      </div>
    </div>
  </div>
  <hr>
  <div class="form-group">
    <div class="row">
      <div class="col-xs-6">
        <div class="control-checkbox" style="margin-top: 25px;">
          <input type="checkbox" disabled="true" {{ $rate->DiscStubPrint ? "checked" : ""}}>
          <label>Enable Discount Stub</label>
        </div>
      </div>
      <div class="col-xs-6">
        <label for="">Discount Stub Validity:</label>
        <input type="number" class="form-control" style="width: 100px;display:inline-block;"
          disabled="true" value="{{ $rate->DiscValidity }}"> (days)
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="">Discount Stub Msg:</label>
    <input type="text" class="form-control" disabled="true" value="{{ $rate->DiscStubMsg }}">
  </div>
  <hr style="margin: 0px 0px 10px 0px;">
  <div class="form-group text-center">
    @if(\Auth::user()->checkAccessById(2, "A"))
    <a class="btn btn-md btn-success" href="{{ route('branchs.rates.index', [$branch, 'action' => 'new']) }}">
      <i class="fa fa-plus"></i> New
    </a>
    @endif
    @if($rate && \Auth::user()->checkAccessById(2, "E"))
    <a class="btn btn-md btn-info" href="{{ route('branchs.rates.index', [$branch, 'action' => 'edit', 'tmplate_id' => $rate->tmplate_id]) }}">
      <i class="fas fa-pencil-alt"></i> Edit
    </a>
    @endif
    <a class="btn btn-md btn-default" href="{{ route('branchs.index', ['corpID' => $branch->corp_id]) }}">
      <i class="fa fa-reply"></i> Back
    </a>
  </div>
</div>
<div class="col-md-8" style="border-left: 1px solid #d2d2d2;padding: 0px;">
  @if($rate->charge_mode == 1 || empty($rate->charge_mode))
  <form action="{{ route('branchs.rates.details', [$branch, $rate]) }}" method="POST">
    <input type="hidden" name="_method" value="PUT">
    {{ csrf_field() }}
    <table class="table borderred">
      <thead>
        <tr>
          <th></th>
          <th colspan="2">Time Zone 1</th>
          <th colspan="2">Time Zone 2</th>
          <th colspan="2">Time Zone 3</th>
        </tr>
        <tr>
          <th>Stn</th>
          <th>Min Change</th>
          <th>Per Minute</th>
          <th>Min Change</th>
          <th>Per Minute</th>
          <th>Min Change</th>
          <th>Per Minute</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rate->details()->orderBy('nKey', 'ASC')->get() as $detail)
        <tr>
          <td style="vertical-align: middle;">{{ $detail->PC_No }}</td>
          <td>
            <input type="text" step="any" class="form-control" value="{{ $detail->MinAmt1 }}" name="detail[{{ $detail->nKey }}][MinAmt1]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
          </td>
          <td>
            <input type="text" class="form-control" value="{{ $detail->Net_1 }}" name="detail[{{ $detail->nKey }}][Net_1]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
          </td>
          <td>
            <input type="text" class="form-control" value="{{ $detail->MinAmt2 }}" name="detail[{{ $detail->nKey }}][MinAmt2]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
          </td>
          <td>
            <input type="text" class="form-control" value="{{ $detail->Net_2 }}" name="detail[{{ $detail->nKey }}][Net_2]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
          </td>
          <td>
            <input type="text" class="form-control" value="{{ $detail->MinAmt3 }}" name="detail[{{ $detail->nKey }}][MinAmt3]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
          </td>
          <td>
            <input type="text" class="form-control" value="{{ $detail->Net_3 }}" name="detail[{{ $detail->nKey }}][Net_3]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
          </td>
        </tr>
        @endforeach
        @if($rate->details()->count() == 0)
        <tr>
          <td colspan="7"><strong>No data to display</strong></td>
        </tr>
        @endif
      </tbody>
    </table>
    @if(\Auth::user()->checkAccessById(2, "E") && $rate->details()->count() > 0)
    <div class="box-assign nohide">
      <hr>
      <table class="table borderred">
        <thead>
          <tr>
            <th></th>
            <th>Min Change</th>
            <th>Per Minute</th>
            <th>Min Change</th>
            <th>Per Minute</th>
            <th>Min Change</th>
            <th>Per Minute</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <button class="btn btn-md btn-success" type="button">
                <i class="fa fa-magic"></i>
              </button>
            </td>
            <td>
              <input type="text" step="any" class="form-control" placeholder="0.00">
            </td>
            <td>
              <input type="text" step="any" class="form-control" placeholder="0.00">
            </td>
            <td>
              <input type="text" step="any" class="form-control" placeholder="0.00">
            </td>
            <td>
              <input type="text" step="any" class="form-control" placeholder="0.00">
            </td>
            <td>
              <input type="text" step="any" class="form-control" placeholder="0.00">
            </td>
            <td>
              <input type="text" step="any" class="form-control" placeholder="0.00">
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    @endif
    <hr>
    @if(\Auth::user()->checkAccessById(2, "E") && $rate->details()->count() > 0)
    <div class="col-md-12 text-right">
      <button class="btn btn-md btn-success btn-save">
        <i class="fa fa-save"></i> Save
      </button>
    </div>
    @endif
  </form>
  @elseif($rate->charge_mode == 2)
  <form action="{{ route('branchs.rates.details', [$branch, $rate]) }}" method="POST">
    <input type="hidden" name="_method" value="PUT">
    {{ csrf_field() }}
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#zone1" aria-controls="zone1" role="tab" data-toggle="tab">Timezone 1</a></li>
      <li role="presentation"><a href="#zone2" aria-controls="zone2" role="tab" data-toggle="tab">Timezone 2</a></li>
      <li role="presentation"><a href="#zone3" aria-controls="zone3" role="tab" data-toggle="tab">Timezone 3</a></li>
    </ul>

    <div class="tab-content">
      @for($i = 1; $i <= 3; $i++)
      <div role="tabpanel" class="tab-pane {{ $i == 1 ? 'active' : ''}}" id="zone{{ $i }}">
        <table class="table borderred">
          <thead>
            <tr>
              <th>Stn</th>
              <th>5 mins</th>
              <th>10 mins</th>
              <th>15 mins</th>
              <th>20 mins</th>
              <th>25 mins</th>
              <th>30 mins</th>
              <th>35 mins</th>
              <th>40 mins</th>
              <th>45 mins</th>
              <th>50 mins</th>
              <th>55 mins</th>
              <th>60 mins</th>
              <th>Min Charge</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rate->details()->orderBy('nKey', 'ASC')->get() as $detail)
            <tr>
              <td>{{ $detail->pcNo }}</td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_5"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_5]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_10"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_10]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_15"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_15]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_20"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_20]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_25"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_25]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_30"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_30]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_35"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_35]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_40"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_40]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_45"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_45]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_50"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_50]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_55"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_55]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_60"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_60]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["MinAmt{$i}"] }}" 
                  name="detail[{{ $detail->nKey }}][MinAmt{{$i}}]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
              </td>
            </tr>
            @endforeach
            @if($rate->details()->count() == 0)
              <tr>
                <td colspan="14"><strong>No data to display</strong></td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
      @endfor
      @if(\Auth::user()->checkAccessById(2, "E") && $rate->details()->count() > 0)
      <div class="box-assign nohide">
        <hr>
        <table class="table borderred">
          <thead>
            <tr>
              <th></th>
              <th>5 mins</th>
              <th>10 mins</th>
              <th>15 mins</th>
              <th>20 mins</th>
              <th>25 mins</th>
              <th>30 mins</th>
              <th>35 mins</th>
              <th>40 mins</th>
              <th>45 mins</th>
              <th>50 mins</th>
              <th>55 mins</th>
              <th>60 mins</th>
              <th>Min Charge</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <button class="btn btn-md btn-success" type="button">
                  <i class="fa fa-magic"></i>
                </button>
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
              <td>
                <input type="text" class="form-control" placeholder="0.00">
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      @endif
    </div>
    <hr>
    @if(\Auth::user()->checkAccessById(2, "E") && $rate->details()->count() > 0)
    <div class="col-md-12 text-right">
      <button class="btn btn-md btn-success btn-save">
        <i class="fa fa-save"></i> Save
      </button>
    </div>
    @endif
  </form>
  @endif
</div>
@endif
