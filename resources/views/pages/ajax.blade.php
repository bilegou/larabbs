<!DOCTYPE html>  
<html>
<head>  
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<title>Hello, World</title>  
<style type="text/css">  
html{height:100%}  
body{height:100%;margin:0px;padding:0px}  
#container{height:50%;width:50%;margin:100px auto;}  
</style>  
<script type="text/javascript" src="http://api.map.baidu.com/api?v=3.0&ak=jXvcAn6tzT3CpQNCm6yAmDg9epXQDUnM">
//v3.0版本的引用方式：src="http://api.map.baidu.com/api?v=3.0&ak=您的密钥"
</script>
<script type="text/javascript" src="js/jquery.js"></script>
</head>  

<input type="text" id="site">
<div id="longitude"></div>
<div id="latitude"></div>
<div id="allmap" style="height:700px;width:800px;"></div>
 
<body>  
<div id="container"></div> 
<script type="text/javascript"> 

 var map = new BMap.Map("allmap");
    var point = new BMap.Point(116.331398,39.897445);
    map.centerAndZoom(point,12);
    map.enableScrollWheelZoom(true);
    var geoc = new BMap.Geocoder();    

    map.addEventListener("click", function(e){   
        //通过点击百度地图，可以获取到对应的point, 由point的lng、lat属性就可以获取对应的经度纬度     
        var pt = e.point;
        geoc.getLocation(pt, function(rs){
            //addressComponents对象可以获取到详细的地址信息
            var addComp = rs.addressComponents;
            var site = addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber;
            //将对应的HTML元素设置值
            $("#site").val(site);
            $("#longitude").html(pt.lng);
            $("#latitude").html(addComp.city);
        });        
    });
</script>  
</body>  
</html>