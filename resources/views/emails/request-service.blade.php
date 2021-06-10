<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8" />
    </head>
    <body>
        <p>Hello,</P>
        <p><strong>{{ $username }}</strong> from company <strong>{{ $company_name }}</strong> has requested service with the below details:</p>
        <table width="100%">
            <tr>
                <td width="50%">Requester Email :</td>
                <td width="50%">{{ $requester_email }}</td>
            </tr>
            <tr>
                <td width="50%">Requester Phone :</td>
                <td width="50%">{{ $phone_number }}</td>
            </tr>
            <tr>
                <td width="50%">Machine Name :</td>
                <td width="50%">{{ $machine_name }}</td>
            </tr>
            <tr>
                <td width="50%">Machine Type :</td>
                <td width="50%">{{ $machine_type }}</td>
            </tr>
            <tr>
                <td width="50%">PLC Software Version :</td>
                <td width="50%">{{ $firmware_version }}</td>
            </tr>
            <tr>
                <td width="50%">Serial Number :</td>
                <td width="50%">{{ $serial_number }}</td>
            </tr>
        </table>
    </body>
</html>
