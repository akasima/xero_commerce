<?php

/**
 * Copyright (C) 2007 INICIS Inc.
 *
 * 해당 라이브러리는 절대 수정되어서는 안됩니다.
 * 임의로 수정된 코드에 대한 책임은 전적으로 수정자에게 있음을 알려드립니다.
 *
 */
require_once ( "INIDFN.php" );
require_once ( "INIXml.php" );

/* ----------------------------------------------------- */
/* Global Variables                                    */
/* ----------------------------------------------------- */
extract($_POST);
extract($_GET);


/* ----------------------------------------------------- */
/* Global Function                                     */
/* ----------------------------------------------------- */

function Base64Encode($str) {
    return substr(chunk_split(base64_encode($str), 64, "\n"), 0, -1) . "\n";
}

function GetMicroTime() {
    list($usec, $sec) = explode(" ", microtime());
    return (float) $usec + (float) $sec;
}

function SetTimestamp() {
    $m = explode(' ', microtime());
    list($totalSeconds, $extraMilliseconds) = array($m[1], (int) round($m[0] * 1000, 3));
    return date("Y-m-d H:i:s", $totalSeconds) . ":$extraMilliseconds";
}

/* ----------------------------------------------------- */
/* LOG Class                                           */
/* ----------------------------------------------------- */

class INILog {

    var $handle;
    var $type;
    var $log;
    var $debug_mode;
    var $array_key;
    var $debug_msg;
    var $starttime;
    var $homedir;
    var $mid;
    var $mkey;
    var $mergelog;

    function __construct($request) {
        $this->debug_msg = array("", "CRITICAL", "ERROR", "NOTICE", "4", "INFO", "6", "DEBUG", "8");
        $this->debug_mode = $request["debug"];
        $this->type = $request["type"];
        $this->log = $request["log"];
        $this->homedir = $request["inipayhome"];
        $this->mid = $request["mid"];
        $this->starttime = GetMicroTime();
    }

    function StartLog() {
        if ($this->log == "false")
            return true;

        if ($this->type == "chkfake")
            $type = "securepay";
        else
            $type = $this->type;
        if ($this->mergelog == "1")
            $logfile = $this->homedir . "/log/" . PROGRAM . "_" . $type . "_mergelog_" . date("ymd") . ".log";
        else
            $logfile = $this->homedir . "/log/" . PROGRAM . "_" . $type . "_" . $this->mid . "_" . date("ymd") . ".log";
        $this->handle = fopen($logfile, "a+");
        if (!$this->handle)
            return false;
        $this->WriteLog(INFO, "START " . PROGRAM . " " . $this->type . " (V" . VERSION . "-" . BUILDDATE . ")(OS:" . php_uname('s') . php_uname('r') . ",PHP:" . phpversion() . ")");
        return true;
    }

    function WriteLog($debug, $data) {
        if ($this->log == "false" || !$this->handle)
            return;

        if (!$this->debug_mode && $debug >= DEBUG)
            return;

        $pfx = $this->debug_msg[$debug] . "\t[" . SetTimestamp() . "] <" . getmypid() . "> ";
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                fwrite($this->handle, $pfx . $key . ":" . $val . "\r\n");
            }
        } else {
            fwrite($this->handle, $pfx . $data . "\r\n");
        }
        fflush($this->handle);
    }

    function CloseLog($msg) {
        if ($this->log == "false")
            return;

        $laptime = GetMicroTime() - $this->starttime;
        $this->WriteLog(INFO, "END " . $this->type . " " . $msg . " Laptime:[" . round($laptime, 3) . "sec]");
        $this->WriteLog(INFO, "===============================================================");
        fclose($this->handle);
    }

}

/* ----------------------------------------------------- */
/* Data Class			                                     */
/* -TID Generate Function	                             */
/* -Data Encrypt Function	                             */
/* -Check Field Function	                             */
/* ----------------------------------------------------- */

class INIData {

    //----------------------------
    //Common
    //----------------------------
    var $m_Type;
    var $m_EscrowType;
    var $m_ErrCode;
    var $m_ErrMsg;
    var $m_EncBody;
    var $m_PG1;
    var $m_PG2;
    var $m_PG1IP;
    var $m_PG2IP;
    //----------------------------
    //IFD 요청필드
    //----------------------------
    var $m_sCmd;
    var $m_sCrypto;
    var $m_sTID;
    var $m_sPayMethod;
    var $m_MPubSN;
    var $m_PIPGPubSN;
    var $m_TXPGPubSN;
    var $m_TXVersion;
    var $m_sMsg;
    var $m_sHead;
    var $m_sBody;
    var $m_sTail;
    var $m_sEncrypted;
    var $m_sSessionKey;
    //----------------------------
    //IFD 응답헤더 필드
    //----------------------------
    var $m_FlgCrypto;
    var $m_FlgSign;
    var $m_Cmd;
    var $m_Body;
    //Xml Data 
    var $m_Xml = array();
    var $m_REQUEST = array();
    var $m_REQUEST2 = array(); //User Defined Entity
    var $m_RESULT = array();  //Encrypted 필드 hash table
    var $m_RESULT2 = array(); //PG Added Entity

    function __construct($request, $request2) {
        $this->m_Xml = NULL;

        $this->m_REQUEST = $request;
        $this->m_REQUEST2 = $request2;

        $this->m_Type = $this->m_REQUEST["type"];
        if ($this->m_Type == TYPE_CANCEL) {
            $this->m_sCmd = CMD_REQ_CAN;
            $this->m_sCrypto = FLAG_CRYPTO_3DES;
        }
        //가상계좌 부분환불 추가
        else if (( $this->m_Type == TYPE_REPAY) || ( $this->m_Type == TYPE_VACCTREPAY)) {
            $this->m_sCmd = CMD_REQ_PRTC;
            $this->m_sCrypto = FLAG_CRYPTO_3DES;
        } else if ($this->m_Type == TYPE_REFUND) { //가상계좌환불(09.08.05)
            $this->m_sCmd = CMD_REQ_RFD;
            $this->m_sCrypto = FLAG_CRYPTO_3DES;
        } else {
            $this->m_sCmd = CMD_REQ_PAY;
            $this->m_sCrypto = FLAG_CRYPTO_3DES;
        }

        $this->m_TXVersion = sprintf("%-4.4s", VERSION) .
                sprintf("B%-6.6s", BUILDDATE) .
                sprintf("%-5.5s", $this->m_Type) .
                sprintf("%-10.10s", php_uname('s')) .
                sprintf("%-3.3s", "PHP") . //modulescript
                sprintf("%-10.10s", "chkfake") . //moduledesc
                sprintf("%-30.30s", php_uname('r') . "PHP:" . phpversion()) //ETCINFO
        ;
    }

    function CheckField() {
        //---------------------------------
        //공통
        //---------------------------------
        if (trim($this->m_REQUEST["inipayhome"]) == "") {
            $this->m_ErrCode = NULL_DIR_ERR;
            $this->m_ErrMsg = "inipayhome";
            return false;
        }
        if (trim($this->m_REQUEST["mid"]) == "") {
            $this->m_ErrCode = NULL_MID_ERR;
            $this->m_ErrMsg = "mid";
            return false;
        }
        if (trim($this->m_REQUEST["type"]) == "") {
            $this->m_ErrCode = NULL_TYPE_ERR;
            $this->m_ErrMsg = "type";
            return false;
        }
        if (trim($this->m_REQUEST["admin"]) == "") {
            $this->m_ErrCode = NULL_KEYPW_ERR;
            $this->m_ErrMsg = "admin";
            return false;
        }
        //---------------------------------
        //type별로
        //---------------------------------
        if ($this->m_Type == TYPE_CANCEL) {
            if (trim($this->m_REQUEST["tid"]) == "") {
                $this->m_ErrCode = NULL_TID_ERR;
                $this->m_ErrMsg = "tid";
                return false;
            }
        }
        //가상계좌 부분환불도 부분환불 로직에 추가
        else if (( $this->m_Type == TYPE_REPAY ) || ( $this->m_Type == TYPE_VACCTREPAY )) {
            if (trim($this->m_REQUEST["oldtid"]) == "") {
                $this->m_ErrCode = NULL_TID_ERR;
                $this->m_ErrMsg = "oldtid";
                return false;
            }
            if (trim($this->m_REQUEST["price"]) == "") {
                $this->m_ErrCode = NULL_PRICE_ERR;
                $this->m_ErrMsg = "price";
                return false;
            }
            if (trim($this->m_REQUEST["confirm_price"]) == "") {
                $this->m_ErrCode = NULL_CONFIRM_PRICE_ERR;
                $this->m_ErrMsg = "confirm_price";
                return false;
            }

            //가상계좌 부분환불 로직에서는 계조번호,은행코드,계좌주명이 필수 
            if ($this->m_Type == TYPE_VACCTREPAY) {
                if (trim($this->m_REQUEST["refundacctnum"]) == "") {
                    $this->m_ErrCode = NULL_FIELD_REFUNDACCTNUM;
                    $this->m_ErrMsg = "환불계좌번호";
                    return false;
                }
                if (trim($this->m_REQUEST["refundbankcode"]) == "") {
                    $this->m_ErrCode = NULL_FIELD_REFUNDBANKCODE;
                    $this->m_ErrMsg = "환불은행코드";
                    return false;
                }
                if (trim($this->m_REQUEST["refundacctname"]) == "") {
                    $this->m_ErrCode = NULL_FIELD_REFUNDACCTNAME;
                    $this->m_ErrMsg = "환불계좌주성명";
                    return false;
                }
            }
        } else if ($this->m_Type == TYPE_REFUND) {
            if (trim($this->m_REQUEST["tid"]) == "") {
                $this->m_ErrCode = NULL_TID_ERR;
                $this->m_ErrMsg = "tid";
                return false;
            }
            if (trim($this->m_REQUEST["racctnum"]) == "") {
                $this->m_ErrCode = NULL_FIELD_REFUNDACCTNUM;
                $this->m_ErrMsg = "환불계좌번호";
                return false;
            }
            if (trim($this->m_REQUEST["rbankcode"]) == "") {
                $this->m_ErrCode = NULL_FIELD_REFUNDBANKCODE;
                $this->m_ErrMsg = "환불은행코드";
                return false;
            }
            if (trim($this->m_REQUEST["racctname"]) == "") {
                $this->m_ErrCode = NULL_FIELD_REFUNDACCTNAME;
                $this->m_ErrMsg = "환불계좌주성명";
                return false;
            }
        }
        return true;
    }

    function MakeRN() {
        list($usec, $sec) = explode(" ", microtime());
        $datestr = date("YmdHis", $sec) . substr($usec, 2, 3); //YYYYMMDDHHMMSSSSS
        return Base64Encode($datestr . rand(10000, 99999));
    }

    function MakeTID() {
        list($usec, $sec) = explode(" ", microtime());
        $datestr = date("YmdHis", $sec) . substr($usec, 2, 3); //YYYYMMDDHHMMSSSSS

        $datestr_con = substr($datestr, 0, 14) . substr($datestr, 15, 2); //YYYYMMDDHHMMSSxSS 중간의 x값은 버림(milli second의 첫번째 자리수)

        mt_srand(getmypid() * mt_rand(1, 999));  //mt_rand 하기전에 srand 로 seed 적용 , seed key = pid * mt_rand(1,999)
        //pgid + mid + 16자리 날짜및 시간 + random_key 4자리 (seed적용)
        $this->m_sTID = $this->m_REQUEST["pgid"] . $this->m_REQUEST["mid"] . $datestr_con . mt_rand(1000, 9999);
        if (strlen($this->m_sTID) != TID_LEN) {
            return false;
        }
        $this->m_REQUEST["tid"] = $this->m_sTID;
        return true;
    }

    function GenRand($length, $nullOk, $ascii = false) {
        # mt_srand( 99999 );
        $out = '';
        for ($i = 0; $i < $length; $i++)
            $out .= chr(mt_rand($nullOk ? 0 : 1, $ascii ? 127 : 255 ));
        return $out;
    }

    function MakeEncrypt($INICrypto) {
        //generate key/iv
        $key = $this->GenRand(MAX_KEY_LEN, true);
        $iv = $this->GenRand(MAX_IV_LEN, true);

        //make XML
        $xml = new XML();
        if ($this->m_Type == TYPE_FORMPAY) {
            
        } else if ($this->m_Type == TYPE_RECEIPT) {
            $PI = $xml->add_node("", PAYMENTINFO);
            $PM = $xml->add_node($PI, PAYMENT);
            $CS = $xml->add_node($PM, NM_CSHR);
            $PD = $xml->add_node($CS, TX_CSHR_APPLPRICE, $this->m_REQUEST["cr_price"]);
            $PD = $xml->add_node($CS, TX_CSHR_SUPPLYPRICE, $this->m_REQUEST["sup_price"]);
            $PD = $xml->add_node($CS, TX_CSHR_TAX, $this->m_REQUEST["tax"]);
            $PD = $xml->add_node($CS, TX_CSHR_SERVICEPRICE, $this->m_REQUEST["srvc_price"]);
            $PD = $xml->add_node($CS, TX_CSHR_TYPE, $this->m_REQUEST["useopt"]);
            $PD = $xml->add_node($CS, TX_CSHR_REGNUM, $this->m_REQUEST["reg_num"]);
            $PD = $xml->add_node($CS, TX_CSHR_COMPANYNUM, $this->m_REQUEST["companynumber"]);
            /*
              $PD = $xml->add_node($CS,		TX_CSHR_OPENMARKET, 			$this->m_REQUEST[""]		);
              $PD = $xml->add_node($CS,		TX_CSHR_SUBCNT, 					$this->m_REQUEST[""]		);
              $PD = $xml->add_node($CS,		TX_CSHR_SUBCOMPANYNAME1, 	$this->m_REQUEST[""]		);
              $PD = $xml->add_node($CS,		TX_CSHR_SUBCOMPANYNUM1, 	$this->m_REQUEST[""]		);
              $PD = $xml->add_node($CS,		TX_CSHR_SUBREGNUM1, 			$this->m_REQUEST[""]		);
              $PD = $xml->add_node($CS,		TX_CSHR_SUBMID1, 					$this->m_REQUEST[""]		);
              $PD = $xml->add_node($CS,		TX_CSHR_SUBAPPLPRICE1, 		$this->m_REQUEST[""]		);
              $PD = $xml->add_node($CS,		TX_CSHR_SUBSERVICEPRICE1, $this->m_REQUEST[""]		);
             */
        } else if ($this->m_Type == TYPE_CANCEL) {
            $CI = $xml->add_node("", CANCELINFO);
            $CD = $xml->add_node($CI, TX_CANCELTID, $this->m_REQUEST["tid"]);
            $CD = $xml->add_node($CI, TX_CANCELMSG, $this->m_REQUEST["cancelmsg"], array("urlencode" => "1"));
            $CD = $xml->add_node($CI, TX_CANCELREASON, $this->m_REQUEST["cancelcode"]);
            $this->AddUserDefinedEntity(CANCELINFO, "", $xml, $CI);
        } else if ($this->m_Type == TYPE_REPAY) {
            //PartCancelInfo(ROOT)
            $CI = $xml->add_node("", PARTCANCELINFO);
            $CD = $xml->add_node($CI, TX_PRTC_TID, $this->m_REQUEST["oldtid"]);
            $CD = $xml->add_node($CI, TX_PRTC_PRICE, $this->m_REQUEST["price"]);
            $CD = $xml->add_node($CI, TX_PRTC_REMAINS, $this->m_REQUEST["confirm_price"]);
            $CD = $xml->add_node($CI, TX_PRTC_QUOTA, $this->m_REQUEST["cardquota"]);
            $CD = $xml->add_node($CI, TX_PRTC_INTEREST, $this->m_REQUEST["quotainterest"]);
            //I계좌이체 국민은행 부분취소시 계좌번호 계좌주성명을 받음 2011-10-06
            $CD = $xml->add_node($CI, TX_PRTC_NOACCT, $this->m_REQUEST["no_acct"]);
            $CD = $xml->add_node($CI, TX_PRTC_NMACCT, $this->m_REQUEST["nm_acct"], array("urlencode" => "1"));
            //과세,비과세 추가 2014-07-23 by jung.ks
            //$CD = $xml->add_node($CI,               TX_PRTC_TAX,		$this->m_REQUEST["tax"]		);
            //$CD = $xml->add_node($CI,               TX_PRTC_TAXFREE,		$this->m_REQUEST["taxfree"]		);

            $this->AddUserDefinedEntity(PARTCANCELINFO, "", $xml, $CI);
        }
        //가상계좌 부분환불
        else if ($this->m_Type == TYPE_VACCTREPAY) {
            //PartCancelInfo(ROOT)
            $CI = $xml->add_node("", PARTCANCELINFO);
            $CD = $xml->add_node($CI, TX_PRTC_TID, $this->m_REQUEST["oldtid"]);
            $CD = $xml->add_node($CI, TX_PRTC_PRICE, $this->m_REQUEST["price"]);
            $CD = $xml->add_node($CI, TX_PRTC_REMAINS, $this->m_REQUEST["confirm_price"]);
            $CD = $xml->add_node($CI, TX_PRTC_QUOTA, $this->m_REQUEST["cardquota"]);
            $CD = $xml->add_node($CI, TX_PRTC_INTEREST, $this->m_REQUEST["quotainterest"]);
            $CD = $xml->add_node($CI, TX_PRTC_NOACCT, $this->m_REQUEST["refundacctnum"]);
            $CD = $xml->add_node($CI, TX_PRTC_NMACCT, $this->m_REQUEST["refundacctname"], array("urlencode" => "1"));
            $CD = $xml->add_node($CI, TX_PRTC_REFUNDFLGREMIT, $this->m_REQUEST["refundflgremit"]);
            $CD = $xml->add_node($CI, TX_PRTC_REFUNDBANKCODE, $this->m_REQUEST["refundbankcode"]);
            $this->AddUserDefinedEntity(PARTCANCELINFO, "", $xml, $CI);
        } else if ($this->m_Type == TYPE_CAPTURE) {
            $CI = $xml->add_node("", CAPTUREINFO);
            $CD = $xml->add_node($CI, TX_CAPTURETID, $this->m_REQUEST["tid"]);
            $this->AddUserDefinedEntity(CAPTUREINFO, "", $xml, $CI);
        } else if ($this->m_Type == TYPE_ESCROW) {
            if ($this->m_EscrowType == TYPE_ESCROW_DLV) {
                $EI = $xml->add_node("", ESCROWINFO);
                $EC = $xml->add_node($EI, ESCROW_DELIVERY);
                $ED = $xml->add_node($EC, "DLV_Oid", $this->m_REQUEST["oid"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_SOid", $this->m_REQUEST["soid"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_Name", $this->m_REQUEST["dlv_name"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_IP", $this->m_REQUEST["dlv_ip"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_MAC", $this->m_REQUEST["dlv_mac"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_Report", $this->m_REQUEST["dlv_report"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_SendName", $this->m_REQUEST["dlv_sendname"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_SendPost", $this->m_REQUEST["dlv_sendpost"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_SendAddr1", $this->m_REQUEST["dlv_sendaddr1"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_SendAddr2", $this->m_REQUEST["dlv_sendaddr2"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_SendTel", $this->m_REQUEST["dlv_sendtel"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_RecvName", $this->m_REQUEST["dlv_recvname"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_RecvPost", $this->m_REQUEST["dlv_recvpost"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_RecvAddr", $this->m_REQUEST["dlv_recvaddr"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_RecvTel", $this->m_REQUEST["dlv_recvtel"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_ExCode", $this->m_REQUEST["dlv_excode"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_ExName", $this->m_REQUEST["dlv_exname"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_Invoice", $this->m_REQUEST["dlv_invoice"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_Charge", $this->m_REQUEST["dlv_charge"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_InvoiceDay", $this->m_REQUEST["dlv_invoiceday"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_GoodsCode", $this->m_REQUEST["dlv_goodscode"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_Goods", $this->m_REQUEST["dlv_goods"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_GoodsCnt", $this->m_REQUEST["dlv_goodscnt"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_Price", $this->m_REQUEST["price"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_Reserved1", $this->m_REQUEST["dlv_reserved1"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_Reserved2", $this->m_REQUEST["dlv_reserved2"], array("urlencode" => "1"));
                $ED = $xml->add_node($EC, "DLV_Reserved3", $this->m_REQUEST["dlv_reserved3"], array("urlencode" => "1"));
            } else if ($this->m_EscrowType == TYPE_ESCROW_CNF) {
                //PluginIn!!
            } else if ($this->m_EscrowType == TYPE_ESCROW_DNY) {
                //PluginIn!!
            } else if ($this->m_EscrowType == TYPE_ESCROW_DNY_CNF) {
                $EI = $xml->add_node("", ESCROWINFO);
                $EC = $xml->add_node($EI, ESCROW_DENYCONFIRM);
                $ED = $xml->add_node($EC, "DCNF_Name", $this->m_REQUEST["dcnf_name"], array("urlencode" => "1"));
            }
        } else if ($this->m_Type == TYPE_REFUND) {
            $CI = $xml->add_node("", CANCELINFO);
            $CD = $xml->add_node($CI, TX_CANCELTID, $this->m_REQUEST["tid"]);
            $CD = $xml->add_node($CI, TX_CANCELMSG, $this->m_REQUEST["cancelmsg"], array("urlencode" => "1"));
            $CD = $xml->add_node($CI, TX_REFUNDACCTNUM, $this->m_REQUEST["racctnum"]);
            $CD = $xml->add_node($CI, TX_REFUNDBANKCODE, $this->m_REQUEST["rbankcode"]);
            $CD = $xml->add_node($CI, TX_REFUNDACCTNAME, $this->m_REQUEST["racctname"], array("urlencode" => "1"));
            $this->AddUserDefinedEntity(CANCELINFO, "", $xml, $CI);
        } else if ($this->m_Type == TYPE_INQUIRY) {
            $CI = $xml->add_node("", INQUIRYINFO);
            $CD = $xml->add_node($CI, TX_INQR_TID, $this->m_REQUEST["tid"]);
            //$this->AddUserDefinedEntity( INQUIRYINFO, "", $xml, $CI );
        } else if ($this->m_Type == TYPE_OPENSUB) {
            $OI = $xml->add_node("", OPENSUBINFO);
            $OD = $xml->add_node($OI, TX_OPENREG_TID, $this->m_REQUEST["oldtid"]);
            $OD = $xml->add_node($OI, TX_OPENREG_MID, $this->m_REQUEST["mid"]);
            //$this->AddUserDefinedEntity( INQUIRYINFO, "", $xml, $CI );
        }
        $this->m_EncBody = $xml->make_xml();

        //encrypt body by SYMM
        if (( $rtv = $INICrypto->SymmEncrypt($this->m_EncBody, $this->m_sEncrypted, $key, $iv)) != OK)
            return $rtv;

        //encrypt key/iv by ASYMM
        if (!$INICrypto->RSAPGPubEncrypt($key, $this->m_sSessionKey))
            return ENC_RSA_ERR;

        $this->m_sSessionKey = Base64Encode($this->m_sSessionKey . $iv);

        return OK;
    }

    function MakeHead() {
        $this->m_sHead = sprintf("%05d", strlen($this->m_sBody));
        $this->m_sHead .= sprintf("%05d", strlen($this->m_sTail));
        $this->m_sHead .= sprintf("%s", $this->m_sCrypto);
        $this->m_sHead .= sprintf("%5s", FLAG_SIGN_SHA1);
        $this->m_sHead .= sprintf("%20s", $this->m_MPubSN);
        $this->m_sHead .= sprintf("%20s", $this->m_PIPGPubSN);
        $this->m_sHead .= sprintf("%20s", $this->m_TXPGPubSN);
        $this->m_sHead .= sprintf("%4s", $this->m_sCmd);
        $this->m_sHead .= sprintf("%10s", $this->m_REQUEST["mid"]);
        $this->m_sHead .= sprintf("%20s", $this->m_REQUEST["price"]);
        $this->m_sHead .= sprintf("%40s", $this->m_REQUEST["tid"]);

        $this->m_sMsg = $this->m_sHead . $this->m_sBody . $this->m_sTail;
        return true;
    }

    /*
      /* Add User Defined Entity
      /* Sample
      /* $inipay->SetXPath("INIpay/GoodsInfo/UserDefined1","value1");
      /* $inipay->SetXPath("INIpay/GoodsInfo/UserDefined2","value2");
      /* $inipay->SetXPath("INIpay/GoodsInfo/Goods/UserDefined3","value3");
      /* $inipay->SetXPath("INIpay/GoodsInfo/Goods/UserDefined4","value4");
      /* $inipay->SetXPath("INIpay/BuyerInfo/UserDefined5","value5");
      /* $inipay->SetXPath("INIpay/BuyerInfo/UserDefined6","value6");
      /* $inipay->SetXPath("INIpay/PaymentInfo/Payment/UserDefined7","value7");
      /* $inipay->SetXPath("INIpay/PaymentInfo/Payment/UserDefined8","value8");
      /* $inipay->SetXPath("INIpay/ManageInfo/UserDefined9","value9");
      /* $inipay->SetXPath("INIpay/ReservedInfo/UserDefined10","value10");
     */

    //
    function AddUserDefinedEntity($info_node, $downnode, $xml, $upnode) {
        foreach ($this->m_REQUEST2 as $key => $val) {
            $node = explode('/', $key);
            $node_cnt = count($node);

            if ($node_cnt < 3)
                continue; //minumum 3
            if ($node[1] != $info_node)
                continue;
            if ($node_cnt == 3 && $downnode != "")
                continue;
            if ($node_cnt == 4 && $downnode != $node[2])
                continue;

            $last_node = substr(strrchr($key, '/'), 1);
            $xml->add_node($upnode, $last_node, $val, array("urlencode" => "1"));
        }
    }

    function MakeBody() {
        $xml = new XML();

        //ROOT(INIpay) ROOT를 ROOTINFO로 수정 2011-05-23
        $root = $xml->add_node("", ROOTINFO);

        if ($this->m_Type == TYPE_CANCEL) {
            //CancelInfo
            $CI = $xml->add_node($root, CANCELINFO);
            $CD = $xml->add_node($CI, TX_SESSIONKEY, $this->m_sSessionKey);
            $CD = $xml->add_node($CI, TX_ENCRYPTED, $this->m_sEncrypted);
        }
        //가상계좌 부분환불추가
        else if (( $this->m_Type == TYPE_REPAY ) || ( $this->m_Type == TYPE_VACCTREPAY )) {
            //PartCancelInfo
            $CI = $xml->add_node($root, PARTCANCELINFO);
            $CD = $xml->add_node($CI, TX_SESSIONKEY, $this->m_sSessionKey);
            $CD = $xml->add_node($CI, TX_ENCRYPTED, $this->m_sEncrypted);
            if ($this->m_Type == TYPE_REPAY) {
                $CIG = $xml->add_node($root, GOODSINFO);
                $CDG = $xml->add_node($CIG, TX_PRTC_TAX, $this->m_REQUEST["tax"]);
                $CDG = $xml->add_node($CIG, TX_PRTC_TAXFREE, $this->m_REQUEST["taxfree"]);
                $CDG = $xml->add_node($CIG, TX_PRTC_CURRENCY, $this->m_REQUEST["currency"]);
            }
        } else if ($this->m_Type == TYPE_REFUND) {
            //CancelInfo
            $CI = $xml->add_node($root, CANCELINFO);
            $CD = $xml->add_node($CI, TX_SESSIONKEY, $this->m_sSessionKey);
            $CD = $xml->add_node($CI, TX_ENCRYPTED, $this->m_sEncrypted);
        }
        //ReservedInfo
        $RI = $xml->add_node($root, RESERVEDINFO);
        $RD = $xml->add_node($RI, TX_MRESERVED1, $this->m_REQUEST["mreserved1"]);
        $RD = $xml->add_node($RI, TX_MRESERVED2, $this->m_REQUEST["mreserved2"]);
        $RD = $xml->add_node($RI, TX_MRESERVED3, $this->m_REQUEST["mreserved3"]);
        $this->AddUserDefinedEntity(RESERVEDINFO, "", $xml, $RI);

        //ManageInfo
        $MI = $xml->add_node($root, MANAGEINFO);
        $MD = $xml->add_node($MI, TX_LANGUAGE, $this->m_REQUEST["language"]);
        $MD = $xml->add_node($MI, TX_URL, $this->m_REQUEST["url"], array("urlencode" => "1"));
        $MD = $xml->add_node($MI, TX_TXVERSION, $this->m_TXVersion);
        //delete UIP(2009.01.21)
        //$MD = $xml->add_node($MI, 	TX_TXUSERIP,  	$this->m_REQUEST["uip"]     		);
        $MD = $xml->add_node($MI, TX_TXUSERID, $this->m_REQUEST["id_customer"], array("urlencode" => "1"));
        $MD = $xml->add_node($MI, TX_TXREGNUM, $this->m_REQUEST["id_regnum"]);
        $this->AddUserDefinedEntity(MANAGEINFO, "", $xml, $MI);

        $this->m_sBody = $xml->make_xml();

        return true;
    }

    function ParseHead($head) {
        $iLen = BODY_LEN + TAIL_LEN;
        if (strlen($head) != MSGHEADER_LEN)
            return RESULT_MSG_FORMAT_ERR;

        $this->m_FlgCrypto = substr($head, $iLen, FLGCRYPTO_LEN);
        $iLen += FLGCRYPTO_LEN;
        $this->m_FlgSign = substr($head, $iLen, FLGSIGN_LEN);
        $iLen += FLGSIGN_LEN;
        $this->m_MPubSN = substr($head, $iLen, MPUBSN_LEN);
        $iLen += MPUBSN_LEN;
        $this->m_PIPGPubSN = substr($head, $iLen, PIPGPUBSN_LEN);
        $iLen += PIPGPUBSN_LEN;
        $this->m_TXPGPubSN = substr($head, $iLen, TXPGPUBSN_LEN);
        $iLen += TXPGPUBSN_LEN;
        $this->m_Cmd = substr($head, $iLen, CMD_LEN);
        $iLen += CMD_LEN;
        $this->m_RESULT[NM_MID] = substr($head, $iLen, MID_LEN);
        $iLen += MID_LEN;
        $this->m_RESULT[NM_TOTPRICE] = substr($head, $iLen, TOTPRICE_LEN);
        $iLen += TOTPRICE_LEN;
        $this->m_RESULT[NM_TID] = substr($head, $iLen, TID_LEN);

        return OK;
    }

    function ParseBody($body, &$encrypted, &$sessionkey) {
        $xml = new XML();
        if (($rtv = $xml->load_xml("", $body)) != OK)
            return $rtv;

        $this->m_Xml = $xml->xml_node;

        //GOODSINFO
        $this->m_RESULT[NM_MOID] = $this->GetXMLData("MOID");


        $ResultCode = $this->GetXMLData("ResultCode");
        $ResultMsg = mb_convert_encoding($this->GetXMLData("ResultMsg"),'UTF-8','EUC-KR');
        //if( substr($ResultCode,2, 4) == "0000" )
        if (strcmp(substr($ResultCode, 2, 4), "0000") == 0) {
            $this->m_RESULT[NM_RESULTCODE] = "00";
            $this->m_RESULT[NM_RESULTMSG] = $ResultMsg;
        } else {
            $this->m_RESULT[NM_RESULTCODE] = "01";
            $this->m_RESULT["NM_ERRORCODE"] = $ResultCode;
            $this->m_RESULT[NM_RESULTMSG] = "[" . $ResultCode . "|" . $ResultMsg . "]";
        }
        $encrypted = $this->GetXMLData("Encrypted");
        $sessionkey = $this->GetXMLData("SessionKey");
        return OK;
    }

    function ParseDecrypt($decrypted) {
        $xml = new XML();
        if (($rtv = $xml->load_xml("", $decrypted)) != OK)
            return $rtv;

        $this->m_Xml = array_merge($this->m_Xml, $xml->xml_node);

        return OK;
    }

    function GTHR($err_code, $err_msg) {
        //Set
        $data["mid"] = $this->m_REQUEST["mid"];
        $data["paymethod"] = $this->m_REQUEST["paymethod"];
        //delete UIP(2009.01.21)
        //$data["user_ip"] = $this->m_REQUEST["uip"];
        $data["tx_version"] = $this->m_TXVersion;
        $data["err_code"] = $err_code;
        $data["err_msg"] = $err_msg;

        // add tid / type  (2014-12-09 by jungc)
        $data["tid"] = $this->m_sTID;
        $data["type"] = $this->m_Type;

        //Send
        $qs = "ctype=TX&";
        foreach ($data as $key => $val)
            $qs .= $key . '=' . urlencode($val) . '&';

        $fp = fsockopen(G_SERVER, G_PORT, $errno, $errstr, G_TIMEOUT_CONNECT);
        if ($fp) {
            $out = "GET " . G_CGI . "?" . $qs . " HTTP/1.0\r\n";
            $out .= "Host: " . G_SERVER . "\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            fclose($fp);
        }
    }

    function ParsePIEncrypted() {
        parse_str($this->m_REQUEST["encrypted"],$temp);
        $this->m_PIPGPubSN = $CertVer;
        $this->m_PG1 = $pg1;
        $this->m_PG2 = $pg2;
        $this->m_PG1IP = $pg1ip;
        $this->m_PG2IP = $pg2ip;
    }

    // Xpath로 안가져온다. 한달을 헛지랄 했다!!
    // added by ddaemiri, 2007.09.03
    function GetXMLData($node) {
        $content = $this->m_Xml[$node . "[1]"]["text"];
        if ($this->m_Xml[$node . "[1]"]["attr"]["urlencode"] == "1")
            $content = urldecode($content);
        return $content;
    }

}

/* ----------------------------------------------------- */
/* Crypto Class			                                   */
/* PHP4.2 & OpenSSL 필요)      	                	     */
/* ----------------------------------------------------- */

class INICrypto {

    var $homedir;
    var $mid;
    var $admin;
    var $pgpubkeyid = NULL;
    var $mprivkeyid = NULL;
    var $mkey;

    function __construct($request) {
        $this->homedir = $request["inipayhome"];
        $this->mid = $request["mid"];
        $this->admin = $request["admin"];
        $this->mkey = null;
    }

    function LoadPGPubKey(&$pg_pubcert_SN) {
        $fp = fopen($this->homedir . "/key/pgcert.pem", "r");
        if (!$fp)
            return NULL_PGCERT_FP_ERR;
        $pub_key = fread($fp, 8192);
        if (!$pub_key) {
            fclose($fp);
            return NULL_PGCERT_FP_ERR;
        }
        fclose($fp);

        $this->pgpubkeyid = openssl_get_publickey($pub_key);
        if (!$this->pgpubkeyid)
            return NULL_PGCERT_ERR;

        $pg_pubcert = openssl_x509_parse($pub_key);
        if (!$pg_pubcert)
            return NULL_X509_ERR; //The structure of the returned data is (deliberately) not yet documented
        $pg_pubcert_SN = $pg_pubcert["serialNumber"];

        return OK;
    }

    function UpdatePGPubKey($pgpubkey) {
        $f_org = $this->homedir . "/key/pgcert.pem";
        $f_new = $this->homedir . "/key/.pgcert.pem.tmp";
        $fp = fopen($f_new, "w");
        if (!$fp)
            return PGPUB_UPDATE_ERR;
        fwrite($fp, $pgpubkey);
        fclose($fp);

        //rename
        if (!rename($f_new, $f_org))
            return PGPUB_UPDATE_ERR;
        return OK;
    }

    function LoadMPubKey(&$m_pubcert_SN) {
        if ($this->mkey == "1")
            $fp = fopen($this->homedir . "/key/mkey/mcert.pem", "r");
        else
            $fp = fopen($this->homedir . "/key/" . $this->mid . "/mcert.pem", "r");
        if (!$fp)
            return NULL_MCERT_FP_ERR;
        $pub_key = fread($fp, 8192);
        if (!$pub_key) {
            fclose($fp);
            return NULL_MCERT_FP_ERR;
        }
        fclose($fp);

        $m_pubcert = openssl_x509_parse($pub_key);
        if (!$m_pubcert)
            return NULL_X509_ERR; //The structure of the returned data is (deliberately) not yet documented
        $m_pubcert_SN = $m_pubcert["serialNumber"];

        return OK;
    }

    function LoadMPrivKey() {
        /*
          //get keypw
          $fp=fopen( $this->homedir . "/key/" . $this->mid . "/keypass.enc", "r");
          if( !$fp ) return GET_KEYPW_FILE_OPEN_ERR;
          $enckey=fread($fp, 8192);
          if( !$enckey ) return GET_KEYPW_FILE_OPEN_ERR;
          fclose($fp);
          if( !$this->SymmDecrypt( base64_decode($enckey), &$keypwd, $this->admin, IV ) )
          return GET_KEYPW_DECRYPT_FINAL_ERR;
         */
        $keypwd = $this->admin;

        //load mpriv key
        if ($this->mkey == "1")
            $fp = fopen($this->homedir . "/key/mkey/mpriv.pem", "r");
        else
            $fp = fopen($this->homedir . "/key/" . $this->mid . "/mpriv.pem", "r");
        if (!$fp)
            return PRIVKEY_FILE_OPEN_ERR;
        $priv_key = fread($fp, 8192);
        if (!$priv_key) {
            fclose($fp);
            return PRIVKEY_FILE_OPEN_ERR;
        }
        fclose($fp);
        $this->mprivkeyid = openssl_get_privatekey($priv_key, $keypwd);
        if (!$this->mprivkeyid)
        //return INVALID_KEYPASS_ERR;
            return GET_KEYPW_DECRYPT_FINAL_ERR;
        return OK;
    }

    function Sign($body, &$sign) {
        if (!openssl_sign($body, $sign, $this->mprivkeyid)) //default:SHA1
            return SIGN_FINAL_ERR;
        $sign = Base64Encode($sign);
        return OK;
    }

    function Verify($body, $tail) {
        $rtv = openssl_verify($body, base64_decode($tail), $this->pgpubkeyid);
        if (!$rtv)
            return SIGN_CHECK_ERR;
        return OK;
    }

    function Decrypt($sessionkey, $encrypted, &$decrypted) {
        $dec_sesskey = base64_decode($sessionkey);
        $src = substr($dec_sesskey, 0, strlen($dec_sesskey) - MAX_IV_LEN);
        if (!$this->RSAMPrivDecrypt($src, $key))
            return DEC_RSA_ERR;
        $iv = substr($dec_sesskey, strlen($dec_sesskey) - MAX_IV_LEN, MAX_IV_LEN);
        if (!$this->SymmDecrypt(base64_decode($encrypted), $decrypted, $key, $iv))
            return DEC_FINAL_ERR;
        return OK;
    }

    function openssl_cipher_block_length($cipher)
    {
        $ivSize = @openssl_cipher_iv_length($cipher);

        // Invalid or unsupported cipher.
        if (false === $ivSize) {
            return false;
        }

        $iv = str_repeat("a", $ivSize);

        // Loop over possible block sizes, from 1 upto 1024 bytes.
        // The upper limit is arbitrary but high enough that is
        // sufficient for any current & foreseeable cipher.
        for ($size = 1; $size < 1024; $size++) {
            $output = openssl_encrypt(
            // Try varying the length of the raw data
                str_repeat("a", $size),

                // Cipher to use
                $cipher,

                // OpenSSL expands the key as necessary,
                // so this value is actually not relevant.
                "a",

                // Disable data padding: php_openssl will return false
                // if the input data's length is not a multiple
                // of the block size.
                //
                // We also pass OPENSSL_RAW_DATA so that PHP does not
                // base64-encode the data (since we just throw it away
                // afterwards anyway)
                OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,

                // Feed it with an IV to avoid nasty warnings.
                // The actual value is not relevant as long as
                // it has the proper length.
                $iv
            );

            if (false !== $output) {
                return $size;
            }
        }

        // Could not determine the cipher's block length.
        return false;
    }

    function SymmEncrypt($src_data, &$enc_data, $key, $iv) {
        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            $size = $this->openssl_cipher_block_length('DES-EDE3-CBC');

            if( !$size ){
                $size = 8;
            }

            $enc_data = openssl_encrypt($this->pkcs5_pad($src_data, $size), 'DES-EDE3-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);
        } else {
            $size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
            $src_data = $this->pkcs5_pad($src_data, $size);
            $cipher = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
            mcrypt_generic_init($cipher, $key, $iv);
            $enc_data = mcrypt_generic($cipher, $src_data);
            mcrypt_generic_deinit($cipher);
            mcrypt_module_close($cipher);
        }

        if (!$enc_data)
            return ENC_FINAL_ERR;
        $enc_data = Base64Encode($enc_data);

        return OK;
    }

    function SymmDecrypt($enc_data, &$dec_data, $key, $iv) {
        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            $dec_data = $this->pkcs5_unpad(openssl_decrypt($enc_data, 'DES-EDE3-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv));
        } else {
            $cipher = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
            mcrypt_generic_init($cipher, $key, $iv);
            $dec_data = mdecrypt_generic($cipher, $enc_data);
            mcrypt_generic_deinit($cipher);
            mcrypt_module_close($cipher);
        }

        if (!$dec_data)
            return false;
        $dec_data = $this->remove_ctrl($dec_data);
        return true;
    }

    function RSAMPrivDecrypt($enc_data, &$dec_data) {
        return openssl_private_decrypt($enc_data, $dec_data, $this->mprivkeyid);
    }

    function RSAMPrivEncrypt($org_data, &$enc_data) {
        if (!openssl_private_encrypt($org_data, $enc_data, $this->mprivkeyid))
            return false;
        $enc_data = Base64Encode($enc_data);
        return true;
    }

    function RSAPGPubEncrypt($org_data, &$enc_data) {
        return openssl_public_encrypt($org_data, $enc_data, $this->pgpubkeyid);
    }

    function FreePubKey() {
        if ($this->pgpubkeyid)
            openssl_free_key($this->pgpubkeyid);
    }

    function FreePrivKey() {
        if ($this->mprivkeyid)
            openssl_free_key($this->mprivkeyid);
    }

    function FreeAllKey() {
        $this->FreePubKey();
        $this->FreePrivKey();
    }

    function remove_ctrl($string) {
        for ($i = 0; $i < strlen($string); $i++) {
            $chr = $string{$i};
            $ord = ord($chr);
            if ($ord < 10)
                $string{$i} = "";
            else
                $string{$i} = $chr;
        }
        return trim($string);
    }

    function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, -1 * $pad);
    }

    function MakeIMStr($pt, $key) {
        if (get_magic_quotes_gpc()) {
            $key = stripslashes($key);
            $pt = stripslashes($pt);
        }
        return substr(chunk_split(base64_encode($this->IMstr($key, $pt)), 64, "\n"), 0, -1);
    }

    function IMstr($pwd, $data) {
        $key[] = '';
        $box[] = '';
        $cipher = '';

        $pwd_length = strlen($pwd);
        $data_length = strlen($data);

        for ($i = 0; $i < 256; $i++) {
            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $data_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }
        return $cipher;
    }

}
?>

