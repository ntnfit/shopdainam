
    <div style="display: inline-grid;">
        @php 
        $bank=sc_config('BankTransfer_info');
        $data = explode(",",  $bank);
        @endphp
        <b style="color:red">Tên tài khoản: {{$data[0]}}. </b>
        <b style="color:red">Số tài khoản: {{$data[1]}}. </b>
        <b style="color:red">Ngân hàng: {{$data[2]}}. </b>
        <b style="color:red">Chi nhánh: {{$data[3]}}. </b>
    </div>

