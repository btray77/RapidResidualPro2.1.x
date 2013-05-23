<?php

/**
 * Description of autoresponders
 * @design For: RapidResidualPro.
 * @author Yasir Rehman
 * @id = 321
 */
session_start();

class autoresponders {

    public $prefix;
    public $db;
    private $responders;

    function __construct($responders, $pid=0) {

        global $db, $prefix;
        $this->prefix = $prefix;
        $this->db = $db;
        if ($pid > 0) {
            $sql_responders = "select psponder from " . $this->prefix . "products where id='$pid'";
            $row_responders = $this->db->get_a_line($sql_responders);
            $this->responders = $row_responders['psponder'];
        }
        else
            $this->responders;
    }

    function process_Autoresponders() {

        if (is_numeric($this->responders))
            return false;
        else {

            $sql_responders = "select * from " . $this->prefix . "responders where rspname2='$this->responders'";
            $row_responders = $this->db->get_a_line($sql_responders);

            $responder_type = $row_responders['rspname']; // RESPONDER TYPE [AWEBER][GET RESPONSE] [ARP] [IMINCA]
            //  GET RESPONSE ///   
            $gr_campaign = $row_responders['gr_campaign'];
            //   AWEBER INFORMATION /// 
            $aweber_meta = $row_responders['aweber_meta'];
            $aweber_unit = $row_responders['aweber_unit'];
            //  ARP RESPONSE ///     
            $arp_list_id = $row_responders['arp_list_id'];
            $trackingtag = $row_responders['trackingtag2'];
            $customfield = $row_responders['trackingtag1'];
            $posturl = $row_responders['posturl'];
            // IMNICA MAIL // 
            switch ($responder_type) {
                case 'Aweber':
                    return $this->aweber($aweber_meta, $aweber_unit);
                    break;

                case 'GetResponse':
                    return $this->getResponse($gr_campaign);
                    break;

                case 'ARP':
                    return $this->ARP($arp_list_id, $trackingtag, $posturl);
                    break;
                case 'ArpReach':
                    return $this->ArpReach($posturl);
                    break;
                case 'Imnica':
                    return $this->imnica($arp_list_id, $customfield);
                    break;
                case 'Constant-Contact':
                    return $this->constantContact($posturl);
                    break;
            }
        }
    }

    function ArpReach($posturl) {
        if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email'])) {
            session_start();
            if (isset($_COOKIE['memberid']))
                $member_id = $_COOKIE['memberid'];
            else if (isset($_SESSION['memberid']))
                $member_id = $_SESSION['memberid'];
            if ($member_id < 1)
                die('Unable to get member Id');
            $data = $this->getMember($member_id);
            $first_name = $data['firstname'];
            $last_name = $data['lastname'];
            $member_email = $data['email'];
        }
        else {
            $first_name = $_POST['firstname'];
            $last_name = $_POST['lastname'];
            $member_email = $_POST['email'];
        }
        $post_fields = array(
            'email_address' => $member_email,
            'first_name' => $first_name,
            'last_name' => $last_name
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, 'ARPR');
        curl_setopt($ch, CURLOPT_URL, $posturl); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); // add POST fields
        $result = curl_exec($ch); // run the whole process

        curl_close($ch);
    }

    function aweber($aweber_meta, $aweber_unit) {

        if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email'])) {
            session_start();
            if (isset($_COOKIE['memberid']))
                $member_id = $_COOKIE['memberid'];
            else if (isset($_SESSION['memberid']))
                $member_id = $_SESSION['memberid'];
            if ($member_id < 1)
                die('Unable to get member Id');
            $data = $this->getMember($member_id);
            $first_name = $data['firstname'];
            $last_name = $data['lastname'];
            $member_email = $data['email'];
        }
        else {
            $first_name = $_POST['firstname'];
            $last_name = $_POST['lastname'];
            $member_email = $_POST['email'];
        }
        $member_name = $first_name . " " . $last_name;
        $data = "name=$member_name&email=$member_email&meta_web_form_id=$aweber_meta&listname=$aweber_unit";
        $url = "http://www.aweber.com/scripts/addlead.pl";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // add POST fields
        $result = curl_exec($ch); // run the whole process

        curl_close($ch);
        return 1;
    }

    function getResponse($gr_campaign) {

        if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email'])) {
            session_start();
            if (isset($_COOKIE['memberid']))
                $member_id = $_COOKIE['memberid'];
            else if (isset($_SESSION['memberid']))
                $member_id = $_SESSION['memberid'];
            if ($member_id < 1)
                die('Unable to get member Id');
            $first_name = $data['firstname'];
            $last_name = $data['lastname'];
            $member_email = $data['email'];
        }
        else {
            $first_name = $_POST['firstname'];
            $last_name = $_POST['lastname'];
            $member_email = $_POST['email'];
        }
        $member_name = $first_name . " " . $last_name;

        $data = "subscriber_name=$member_name&subscriber_email=$member_email&campaign_name=$gr_campaign&getpostdata=get";

        $url = "http://www.getresponse.com/cgi-bin/add.cgi";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // add POST fields
        $result = curl_exec($ch); // run the whole process
        curl_close($ch);
        return 1;
    }

    function ARP($arp_list_id, $trackingtag, $url) {

        if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email'])) {
            session_start();
            if (isset($_COOKIE['memberid']))
                $member_id = $_COOKIE['memberid'];
            else if (isset($_SESSION['memberid']))
                $member_id = $_SESSION['memberid'];
            if ($member_id < 1)
                die('Unable to get member Id');
            $first_name = $data['firstname'];
            $last_name = $data['lastname'];
            $member_email = $data['email'];
        }
        else {
            $first_name = $_POST['firstname'];
            $last_name = $_POST['lastname'];
            $member_email = $_POST['email'];
        }
        $member_name = $first_name . " " . $last_name;

        $data = "first_name=$member_name&email=$member_email&id=$arp_list_id&tracking_tag=$trackingtag";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // add POST fields
        $result = curl_exec($ch); // run the whole process
        curl_close($ch);
        echo $result;
    }

    function imnica($list, $customfield_id) {


        if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email'])) {
            session_start();
            if (isset($_COOKIE['memberid']))
                $member_id = $_COOKIE['memberid'];
            else if (isset($_SESSION['memberid']))
                $member_id = $_SESSION['memberid'];
            if ($member_id < 1)
                die('Unable to get member Id');
            $first_name = $data['firstname'];
            $last_name = $data['lastname'];
            $member_email = $data['email'];
        }
        else {
            $first_name = $_POST['firstname'];
            $last_name = $_POST['lastname'];
            $member_email = $_POST['email'];
        }

        $member_name = $first_name . " " . $last_name;

        $url = "http://www.imnicamail.com/v4/subscribe.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "FormValue_Fields[CustomField$customfield_id]=$member_name&FormValue_Fields[EmailAddress]=$member_email&FormValue_ListID=$list&FormValue_Command=Subscriber.Add"); // add POST fields
        $result = curl_exec($ch); // run the whole process
        curl_close($ch);
        return 1;
    }

    function constantContact($list) {

        if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email'])) {
            session_start();
            if (isset($_COOKIE['memberid']))
                $member_id = $_COOKIE['memberid'];
            else if (isset($_SESSION['memberid']))
                $member_id = $_SESSION['memberid'];
            if ($member_id < 1)
                die('Unable to get member Id');
            $first_name = $data['firstname'];
            $last_name = $data['lastname'];
            $member_email = $data['email'];
        }
        else {
            $first_name = $_POST['firstname'];
            $last_name = $_POST['lastname'];
            $member_email = $_POST['email'];
        }

        $url = "http://" . $_SERVER['HTTP_HOST'] . "/postContact.php";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "email_address=$member_email&first_name=$first_name&last_name=$last_name&list=$list"); // add POST fields
        $result = curl_exec($ch); // run the whole process
        curl_close($ch);


        return 1;
    }

    function getMember($id) {
        $sql = "SELECT * from " . $this->prefix . "members where id = $id";
        $row_members = $this->db->get_a_line($sql);
        $data['firstname'] = $row_members['firstname'];
        $data['lastname'] = $row_members['lastname'];
        $data['email'] = $row_members['email'];
        return $data;
    }

}

?>
