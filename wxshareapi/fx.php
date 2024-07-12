<?php
error_reporting(7);
include "config/config.php";

$fxtitle=$_GET["fxtitle"];

$fxdesc=$_GET["fxdesc"];

$fxpic=$_GET["fxpic"];	//分享的小图标

?>

  wx.config({

      debug: false,

      appId: '<?php echo $appid;?>',

      timestamp: <?php echo time();?>,

      nonceStr: '<?php echo $noncestr;?>',

      signature: '<?php echo $signature;?>',

      jsApiList: [

        'onMenuShareTimeline',

        'onMenuShareAppMessage'

      ]

  });





var shareData = { title: '', desc: '', img: '', link: '' };

shareData.title = '<?php echo $fxtitle;?>';

shareData.desc = '<?php echo $fxdesc;?>';

shareData.img = '<?php echo $fxpic;?>';

shareData.link = '<?php echo $appurl;?>';



wx.ready(function () {

//分享到朋友圈

	wx.onMenuShareTimeline({

		title: shareData.title, // 分享标题fxtitle

		link: shareData.link, // 分享链接

		imgUrl: shareData.img, // 分享图标

		type: '', // 分享类型,music、video或link，不填默认为link

		dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空

		success: function () { 

		},

		cancel: function () { 

		}

	});



//分享给单个朋友

	wx.onMenuShareAppMessage({

		title: shareData.title, // 分享标题

		desc: shareData.desc, // 分享描述

		link: shareData.link, // 分享链接

		imgUrl: shareData.img, // 分享图标

		type: '', // 分享类型,music、video或link，不填默认为link

		dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空

		success: function () { 

		},

		cancel: function () { 

			// 用户取消分享后执行的回调函数

		}

	});





	//分享给QQ好友

	wx.onMenuShareQQ({

		title: shareData.title, // 分享标题

		desc: shareData.desc, // 分享描述

		link: shareData.link, // 分享链接

		imgUrl: shareData.img, // 分享图标

		success: function () { 

		   // 用户确认分享后执行的回调函数

		},

		cancel: function () { 

		   // 用户取消分享后执行的回调函数

		}

	});

});





wx.error(function (res) {

});

