<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Logistic Infotech</title>
</head>
<body style="margin:0px; padding:0px; font-family: arial;">
    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center" style="margin:0px;padding:0;font-weight: 300;">
        <tr>
            <td>
                <table cellspacing="0" cellpadding="0" border="0" width="500" align="center" style="margin:0px auto; padding:0;background:#fff;">
                    <tr>
                        <th style="height:30px;"></th>
                    </tr>
                    <tr>
                        <th>
                            @include('emails.includes.header')
                        </th>
                    </tr>
                    <tr>
                        <th style="height:20px;"></th>
                    </tr>
                    <tr>
                        <th style="height:2px; background-color:#383838; width:100%;"></th>
                    </tr>

                    {{-- Table body --}}
                    <tr>
                        <td style="padding: 5px 30px 17px; font-size: 16px; color:#666666;font-family:Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; -webkit-font-smoothing: antialiased; font-smoothing: antialiased;">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- Table Footer --}}
                    <tr>
                        <td style="background:#f4f8f9; font-size:12px; padding: 8px 15px; text-align:center; font-family:Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; -webkit-font-smoothing: antialiased; font-smoothing: antialiased;">
                            @include('emails.includes.footer')
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>