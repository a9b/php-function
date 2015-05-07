<?php
/**
 * デバックできる状態にする
 *
 * @create  2015/05/07 17:02:40
 * @update  2015/05/07 17:02:41
 * @author  a9b
 * @param   none
 * @return  none
 **/
function debugMode()
{
  error_reporting(-1);
  ini_set('display_errors', 'On');
}//function


/**
 * $hoge['fuga'] のエイリアス
 *
 * e.g.)
 * get($hoge, 'fuga');
 *
 * @create  2015/05/07 16:53:05
 * @update  2015/05/07 16:53:06
 * @author  a9b
 * @param   array  $array 検索対象の配列
 * @param   string $key   検索対象のキー
 * @return  string or null
 **/
function get($array, $key=null)
{
  if (!isset[$key])
  {
    return null;
  }//if

  return $array[$key];
}//function


/**
 * echo のエイリアス
 *
 * @create  2015/05/07 16:27:19
 * @update  2015/05/07 16:27:19
 * @author  a9b
 * @param   string $str 出力したい文字列
 * @return  echo出力
 **/
function e($str)
{
  echo $str;
}//function


/**
 * in_array のラッパー(高速版)
 *
 * @create  2015/05/07 16:27:19
 * @update  2015/05/07 16:27:19
 * @author  a9b
 * @access  public
 * @param   string $needle   検索対象のキー
 * @param   array  $haystack 検索対象の配列
 * @return  boolean
 **/
function inArrayFast($needle, $haystack)
{
  $array = array_flip($haystack);
  return isset($array[$needle]);
}//function


/**
 * is_numeric のラッパー(厳格版)
 *
 * @create  2015/05/07 16:27:19
 * @update  2015/05/07 16:27:19
 * @author  a9b
 * @param   int|float|string $value
 * @return  boolean
 **/
function isIntval($value)
{
  return !is_float($value) && (string)$value === (string)(int)$value;
}//function


/**
 * 文字列から不要なゴミを削除する
 *
 * e.g. )
 * 文字列中のゴミ削除 デフォルト：$dust[] = "\n"; $dust[] = "\t";
 * $dust[] = "\n";
 * $dust[] = "\t";
 * $mix = dustDel($mix, $dust=null);
 *
 * @create  2015/05/07 15:53:46
 * @update  2015/05/07 15:53:46
 * @author  a9b
 * @param   array|string $mix  対象の文字列 or 配列
 * @param   array $dust  削除する文字列
 * @return  mixed array() or string
 */
function dustDel($mix, $dust=null)
{
  if(is_null($dust)){
    $dust[] = "\n";
    $dust[] = "\t";
  }//if

  if(is_array($mix)){
    foreach($mix as $k => $v){
      if(is_array($v)){
        $mix[$k] = dustDel($v);
      }
      else
      {
        $mix[$k] = str_replace($dust, "", $v);
      }//if
    }//foreach
  }
  else
  {
    $mix = str_replace($dust, "", $mix);
  }//if

  return $mix;
}//function


/**
 * サブネットマスクも考慮したIPチェック
 *
 * @create  2015/05/07 15:55:22
 * @update  2015/05/07 15:55:23
 * @author  a9b
 * @access  public
 * @param   mix $allow_ip IP (192.168.11.0/255)
 * @return  bool
 **/
function canAccessIP($allow_ip)
{
  if(!is_array($allow_ip))
  {
    (array)$allow_ip;
  }//if

  $allow_ip = array_filter($allow_ip, function($ip){
    if(is_numeric(strpos($v,'/'))){
      return _canAccessIPMask($v);
    }
    else
    {
      return _canAccessIP($v);
    }//if
  });

  if (is_array($allow_ip))
  {
    return true;
  }//if

  return false;
}//function


function _canAccessIP($ip)
{
  return $_SERVER["REMOTE_ADDR"] === $ip;
}//function

function _canAccessIPMask($mask_ip)
{
  list($ip, $mask_bit) = explode("/", $mask_ip);
  $ip_long   = ip2long($ip) >> (32 - $mask_bit);
  $p_ip_long = ip2long($_SERVER["REMOTE_ADDR"]) >> (32 - $mask_bit);

  return $p_ip_long === $ip_long;
}//function
?>
