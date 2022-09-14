<div class="card-header text-center">
    <h3><strong>{{$certificate['web_site']}}</strong></h3>
</div>
<div class="card-body">
    <center>
        <strong>Your certificate is expring in {{Carbon\Carbon::now()->diffInDays($certificate['expiration_date'], false)}} days.</strong>
        <button type="button" class="btn btn-success btn-sm mx-2" data-toggle="modal" data-target="#remindMeModal">
            Remind Me
        </button>
    </center>

    <hr>

    <div>
        <table class="table table-striped table-light table-hover mx-auto">
            <thead class="thead-dark">
                <tr class="text-center">
                    <th colspan="3">GENERAL INFORMATION</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Resolves To</td>
                    <td>:</td>
                    <td>{{$certificate['web_site']}}</td>
                </tr>
                <tr>
                    <td>Server IP Address</td>
                    <td>:</td>
                    <td>{{$certificate['server_ip_address']}}</td>
                </tr>
                <tr>
                    <td>Valid From</td>
                    <td>:</td>
                    <td>{{$certificate['valid_from_date']->format('d-m-Y h:i:s')}}</td>
                </tr>
                <tr>
                    <td>Valid To</td>
                    <td>:</td>
                    <td>{{$certificate['expiration_date']->format('d-m-Y h:i:s')}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr>

    <div>
        <table class="table table-striped table-light table-hover mx-auto">
            <thead class="thead-dark">
                <tr class="text-center">
                    <th colspan="3">ISSUED FOR</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Common name</td>
                    <td>:</td>
                    <td>{{$additionalData['subject']['CN']}}</td>
                </tr>
                <tr>
                    <td>SAN</td>
                    <td>:</td>
                    <td>{{ implode(', ', $certificate['additional_domains']) }}</td>
                </tr>
                <tr>
                    <td>Organization</td>
                    <td>:</td>
                    <td>{{ isset($additionalData['subject']['O']) ? $additionalData['subject']['O'] : 'NA'}}</td>
                </tr>
                <tr>
                    <td>Organization unit</td>
                    <td>:</td>
                    <td>{{ (isset($additionalData['subject']['OU'])) ? ((gettype($additionalData['subject']['OU']) == 'array') ? implode(', ', $additionalData['subject']['OU']) : $additionalData['subject']['OU']) : 'NA' }}</td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td>:</td>
                    <td>{{ isset($additionalData['subject']['C']) ? $additionalData['subject']['C'] : 'NA'}}</td>
                </tr>
                <tr>
                    <td>State</td>
                    <td>:</td>
                    <td>{{ isset($additionalData['subject']['ST']) ? $additionalData['subject']['ST'] : 'NA'}}</td>
                </tr>
                <tr>
                    <td>Locality</td>
                    <td>:</td>
                    <td>{{ isset($additionalData['subject']['L']) ? $additionalData['subject']['L'] : 'NA'}}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>:</td>
                    <td>{{ isset($additionalData['subject']['AD']) ? $additionalData['subject']['AD'] : 'NA'}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr>

    <div>
        <table class="table table-striped table-light table-hover mx-auto">
            <thead class="thead-dark">
                <tr class="text-center">
                    <th colspan="3">ISSUED BY</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Organization</td>
                    <td>:</td>
                    <td>{{ isset($additionalData['issuer']['O']) ? $additionalData['issuer']['O'] : 'NA' }}</td>
                </tr>
                <tr>
                    <td>Common name</td>
                    <td>:</td>
                    <td>{{ isset($additionalData['issuer']['CN']) ? $additionalData['issuer']['CN'] : 'NA' }}</td>
                </tr>
                <tr>
                    <td>Organization unit</td>
                    <td>:</td>
                    <td>{{ (isset($additionalData['issuer']['OU'])) ? ((gettype($additionalData['issuer']['OU']) == 'array') ? implode(', ', $additionalData['issuer']['OU']) : $additionalData['issuer']['OU']) : 'NA' }}</td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td>:</td>
                    <td>{{ isset($additionalData['issuer']['C']) ? $additionalData['issuer']['C'] : 'NA' }}</td>
                </tr>
                <tr>
                    <td>State</td>
                    <td>:</td>
                    <td>{{ isset($additionalData['issuer']['ST']) ? $additionalData['issuer']['ST'] : 'NA' }}</td>
                </tr>
                <tr>
                    <td>Locality</td>
                    <td>:</td>
                    <td>{{ isset($additionalData['issuer']['L']) ? $additionalData['issuer']['L'] : 'NA' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr>

    <div>
        <table class="table table-striped table-light table-hover mx-auto">
            <thead class="thead-dark">
                <tr class="text-center">
                    <th colspan="3">ADVANCED</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Serial number</td>
                    <td>:</td>
                    <td>{{$additionalData['serialNumberHex']}}</td>
                </tr>
                <tr>
                    <td>Signature algorithm</td>
                    <td>:</td>
                    <td>{{$certificate['signature_algorithm']}}</td>
                </tr>
                <tr>
                    <td>Fingerprint (SHA-1)</td>
                    <td>:</td>
                    <td>{{ $certificate['fingerprint'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="remindMeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 10001;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-body">
                    <form id="reminderForm">
                        <div class="input-group">
                            <input type="hidden" name="ssl_id" value="{{$certificate['id']}}">
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email to get mail before 7 days of expire." required>
                            <div class="input-group-append">
                                <button class="btn btn-primary ml-3">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>