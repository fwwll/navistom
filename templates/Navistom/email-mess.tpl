<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>{{ title }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>

<table cellpadding="0" cellspacing="0" width="600"
       style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; margin:0 auto; padding:10px; border-top:#f39130 3px solid;">
    <tr>
        <td>
            <img src="http://navistom.com/templates/Default/images/navi-logo.png">
        </td>
        <td valign="bottom" align="right">
            {{ date|dateRus }}
        </td>
    </tr>
    <tr>
        <td style="padding-top:10px">
            {{ message }}
        </td>
    </tr>
</table>

</body>
</html>