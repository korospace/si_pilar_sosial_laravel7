<?php

use Illuminate\Http\UploadedFile;
use Shuchkin\SimpleXLS;
use Shuchkin\SimpleXLSX;
use SimpleCSV as SimpleCSV;
use Illuminate\Http\Request;

/**
 * Method Parser.
 *
 * @param string $variableName
 */
function _methodParser(string $variableName): void
{
    $putdata  = fopen("php://input", "r");
    $raw_data = '';

    while ($chunk = fread($putdata, 1024))
        $raw_data .= $chunk;

    fclose($putdata);

    $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

    if(empty($boundary)){
        parse_str($raw_data,$data);
        $GLOBALS[ $variableName ] = $data;
        return;
    }

    $parts = array_slice(explode($boundary, $raw_data), 1);
    $data  = array();

    foreach ($parts as $part) {
        if ($part == "--\r\n") break;

        $part = ltrim($part, "\r\n");
        list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

        $raw_headers = explode("\r\n", $raw_headers);
        $headers = array();
        foreach ($raw_headers as $header) {
            list($name, $value) = explode(':', $header);
            $headers[strtolower($name)] = ltrim($value, ' ');
        }

        if (isset($headers['content-disposition'])) {
            $filename = null;
            $tmp_name = null;
            preg_match(
                '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                $headers['content-disposition'],
                $matches
            );

            if(count($matches) !== 0){
                list(, $type, $name) = $matches;
            }

            if( isset($matches[4]) )
            {
                if( isset( $_FILES[ $matches[ 2 ] ] ) )
                {
                    continue;
                }

                $filename       = $matches[4];
                $filename_parts = pathinfo( $filename );
                $tmp_name       = tempnam( ini_get('upload_tmp_dir'), $filename_parts['filename']);

                $_FILES[ $matches[ 2 ] ] = array(
                    'error'=>0,
                    'name'=>$filename,
                    'tmp_name'=>$tmp_name,
                    'size'=>strlen( $body ),
                    'type'=>preg_replace('/\s+/', '', $value)
                );

                file_put_contents($tmp_name, $body);
            }
            else
            {
                $data[$name] = substr($body, 0, strlen($body) - 2);
            }
        }

    }
    $GLOBALS[ $variableName ] = $data;
    return;
}

/**
 * Transalte Date To English
 *
 * @param string $stringDate
 */
function MonthToEnglish($stringDate)
{
    $bulan_indonesia = [
        'Januari', 'Februari', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember'
    ];

    $bulan_inggris = [
        'January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August',
        'September', 'October', 'November', 'December'
    ];

    $tanggal_baru = str_replace($bulan_indonesia, $bulan_inggris, $stringDate);
    $tanggal_baru = strtr($stringDate, array_combine($bulan_indonesia, $bulan_inggris));

    return $tanggal_baru;
}

/**
 * Transalte Date To Indonesia
 *
 * @param string $stringDate
 */
function MonthToIndonesia($stringDate)
{
    $bulan_indonesia = [
        'Januari', 'Februari', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember'
    ];

    $bulan_inggris = [
        'January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August',
        'September', 'October', 'November', 'December'
    ];

    $tanggal_baru = str_replace($bulan_inggris, $bulan_indonesia, $stringDate);
    $tanggal_baru = strtr($stringDate, array_combine($bulan_inggris, $bulan_indonesia));

    return $tanggal_baru;
}

/**
 * Site Authorization
 *
 * @param Illuminate\Http\Request $request
 * @param string $siteId
 */
function authorizeSite(Request $request, string $siteId) : void 
{
    if ($request->user->user_level->id != 1) {
        if ($request->user->site->id != $siteId) {
            abort(401);
        }
    }
}