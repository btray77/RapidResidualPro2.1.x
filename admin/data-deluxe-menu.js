/*
   Deluxe Menu Data File
   Created by Deluxe Tuner v3.10
   http://deluxe-menu.com
*/


// -- Deluxe Tuner Style Names
var itemStylesNames=["Top Item",];
var menuStylesNames=["Top Menu",];
// -- End of Deluxe Tuner Style Names

//--- Common
var menuIdentifier="";
var isHorizontal=1;
var smColumns=1;
var smOrientation=0;
var dmRTL=0;
var pressedItem=-2;
var itemCursor="default";
var itemTarget="_self";
var statusString="link";
var blankImage="data-deluxe-menu.files/blank.gif";
var pathPrefix_img="";
var pathPrefix_link="";

//--- Dimensions
var menuWidth="";
var menuHeight="21px";
var smWidth="";
var smHeight="";

//--- Positioning
var absolutePos=0;
var posX="10px";
var posY="10px";
var topDX=0;
var topDY=1;
var DX=-5;
var DY=0;
var subMenuAlign="left";
var subMenuVAlign="top";

//--- Font
var fontStyle=["normal 11px Trebuchet MS, Tahoma","normal 11px Trebuchet MS, Tahoma"];
var fontColor=["#000000","#000000"];
var fontDecoration=["none","none"];
var fontColorDisabled="#AAAAAA";

//--- Appearance
var menuBackColor="#FFFFFF";
var menuBackImage="";
var menuBackRepeat="repeat";
var menuBorderColor="#B9B9B9";
var menuBorderWidth="1px";
var menuBorderStyle="solid";
var smFrameImage="";
var smFrameWidth=0;

//--- Item Appearance
var itemBackColor=["#FFFFFF","#A7D7FE"];
var itemBackImage=["",""];
var itemSlideBack=0;
var beforeItemImage=["",""];
var afterItemImage=["",""];
var beforeItemImageW="";
var afterItemImageW="";
var beforeItemImageH="";
var afterItemImageH="";
var itemBorderWidth="0px";
var itemBorderColor=["#FCEEB0","#4C99AB"];
var itemBorderStyle=["solid","solid"];
var itemSpacing=1;
var itemPadding="2px 5px 2px 10px";
var itemAlignTop="left";
var itemAlign="left";

//--- Icons
var iconTopWidth=16;
var iconTopHeight=16;
var iconWidth=16;
var iconHeight=16;
var arrowWidth=7;
var arrowHeight=7;
var arrowImageMain=["data-deluxe-menu.files/arrv_white.gif",""];
var arrowWidthSub=0;
var arrowHeightSub=0;
var arrowImageSub=["data-deluxe-menu.files/arr_black.gif","data-deluxe-menu.files/arr_white.gif"];

//--- Separators
var separatorImage="";
var separatorWidth="100%";
var separatorHeight="3px";
var separatorAlignment="left";
var separatorVImage="";
var separatorVWidth="3px";
var separatorVHeight="100%";
var separatorPadding="0px";

//--- Floatable Menu
var floatable=0;
var floatIterations=1;
var floatableX=1;
var floatableY=1;
var floatableDX=5;
var floatableDY=5;

//--- Movable Menu
var movable=0;
var moveWidth=12;
var moveHeight=20;
var moveColor="#DECA9A";
var moveImage="";
var moveCursor="move";
var smMovable=0;
var closeBtnW=15;
var closeBtnH=15;
var closeBtn="";

//--- Transitional Effects & Filters
var transparency="100";
var transition=14;
var transOptions="";
var transDuration=350;
var transDuration2=200;
var shadowLen=3;
var shadowColor="#B1B1B1";
var shadowTop=0;

//--- CSS Support (CSS-based Menu)
var cssStyle=0;
var cssSubmenu="";
var cssItem=["",""];
var cssItemText=["",""];

//--- Advanced
var dmObjectsCheck=0;
var saveNavigationPath=1;
var showByClick=0;
var noWrap=1;
var smShowPause=200;
var smHidePause=1000;
var smSmartScroll=1;
var topSmartScroll=0;
var smHideOnClick=1;
var dm_writeAll=1;
var useIFRAME=0;
var dmSearch=0;

//--- AJAX-like Technology
var dmAJAX=1;
var dmAJAXCount=21;
var ajaxReload=0;

//--- Dynamic Menu
var dynamic=0;

//--- Popup Menu
var popupMode=0;

//--- Keystrokes Support
var keystrokes=0;
var dm_focus=1;
var dm_actKey=113;

//--- Sound
var onOverSnd="";
var onClickSnd="";

var itemStyles = [
    ["itemWidth=92px","itemHeight=21px","itemBackImage=data-deluxe-menu.files/btn_white.gif,data-deluxe-menu.files/btn_white_blue.gif","fontStyle='normal 11px Tahoma','normal 11px Tahoma'","fontColor=#000000,#000000"],
];
var menuStyles = [
    ["menuBackColor=transparent","menuBorderWidth=0","itemSpacing=1","itemPadding=0px 5px 0px 5px"],
];

var menuItems = [

    ["Home","admin_menu.php", "", "", "", "", "0", "0", "", "", "", ],
    ["Site Settings","", "", "", "", "", "0", "0", "", "", "", ],
        ["|Admin Settings","", "", "", "", "", "", "-1", "", "", "", ],
            ["||My Settings","admin_settings.php", "", "", "", "", "", "", "", "", "", ],
            ["||Extra Admin Management","admin_view.php", "", "", "", "", "", "", "", "", "", ],
        ["|Site Settings","site_settings.php", "", "", "", "", "", "", "", "", "", ],
        ["|System Email Settings","emails.php", "", "", "", "", "", "", "", "", "", ],
    ["Manage Site","", "", "", "", "", "0", "0", "", "", "", ],
        ["|Page Management","", "", "", "", "", "", "", "", "", "", ],
            ["||Custom Content Pages","pages.php", "", "", "", "", "", "", "", "", "", ],
            ["||Manage Mini Blog","blog.php", "", "", "", "", "", "", "", "", "", ],
            ["||Members Area Pages","", "", "", "", "", "", "", "", "", "", ],
                ["|||Index Pages","mindex.php", "", "", "", "", "", "", "", "", "", ],
                ["|||Menus","menus.php", "", "", "", "", "", "", "", "", "", ],
            ["||Squeeze Page Management","squeeze_view.php", "", "", "", "", "", "", "", "", "", ],
            ["||Affiliate Signup","affiliate.php", "", "", "", "", "", "", "", "", "", ],
            ["||JV Partner Signup","jvsign.php", "", "", "", "", "", "", "", "", "", ],
            ["||Legal Page Settings","tpd.php", "", "", "", "", "", "", "", "", "", ],
        ["|Autoresponder Management","", "", "", "", "", "", "", "", "", "", ],
            ["||Aweber Autoresponders","aweber.php", "", "", "", "", "", "", "", "", "", ],
            ["||Get Response Autoresponders","gr.php", "", "", "", "", "", "", "", "", "", ],
            ["||Autoresponse Plus","arp.php", "", "", "", "", "", "", "", "", "", ],
        ["|Member Management","member_view.php", "", "", "", "", "", "", "", "", "", ],
    ["Products","", "", "", "", "", "0", "0", "", "", "", ],
        ["|Product Management","paid_products.php", "", "", "", "", "", "", "", "", "", ],
        ["|Time-Released Content","tcampaigns.php", "", "", "", "", "", "", "", "", "", ],
        ["|Coupon Management","coupon.php", "", "", "", "", "", "", "", "", "", ],
        ["|Affiliate Tools","", "", "", "", "", "", "", "", "", "", ],
            ["||Affiliate Banners","market_banners.php", "", "", "", "", "", "", "", "", "", ],
            ["||Affiliate Emails","affiliate_emails.php", "", "", "", "", "", "", "", "", "", ],
    ["Reports","", "", "", "", "", "0", "0", "", "", "", ],
        ["|Sales Stats","", "", "", "", "", "", "", "", "", "", ],
            ["||All Sales","sales.php", "", "", "", "", "", "", "", "", "", ],
            ["||Last 50 Sales","sales.php?count=50", "", "", "", "", "", "", "", "", "", ],
        ["|Conversion Tracking","conversion.php", "", "", "", "", "", "", "", "", "", ],
    ["Tools","shorturl.php", "", "", "", "", "0", "", "", "", "", ],
        ["|Short Urls","shorturl.php", "", "", "", "", "", "", "", "", "", ],
        ["|My Help Desks","help_desks.php", "", "", "", "", "", "", "", "", "", ],
    ["RRP Support","", "", "", "", "_blank", "0", "0", "", "", "", ],
        ["|Help Desk","http://www.asksteveodette.com", "", "", "", "_blank", "", "", "", "", "", ],
        ["|Support Forum","http://www.rapidresidualpro.com/community/", "", "", "", "_blank", "", "", "", "", "", ],
        ["|PHP Info","server_info.php", "", "", "", "_blank", "", "", "", "", "", ],
    ["Log Out","logout.php", "", "", "", "", "0", "", "", "", "", ],
];

dm_init();