@if (session()->get('message-2'))
    @php
        $fm = explode('|', session()->get('message-2'));
        if (count($fm) > 1) {
            $ftype = $fm[0] == 'error' ? 'danger' : $fm[0];
            $fmessage = $fm[1];
        }
    @endphp
    <div class="container" style="padding-top: 1%;">
        <div class="row">
            <div class="col-md-12">
               <div class="callout callout-{{$ftype}}">
                      <h5>{{ucfirst($ftype)}}</h5>
                      <p>{{$fmessage}}</p>
                </div>
            </div>
        </div>
    </div>
@endif