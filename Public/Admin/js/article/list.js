$(function(){
var _this=this;
var nums = 5;
var Posts=$('.Article-list');
this.dt={};
this.each_data=function(){
	  _this.dt = {
		'pageCount':nums,
	  }
	};
  this.init=function(url){
	_this.each_data();
	 $.ajax({
	  type: "post",
	  url:url, 
	  data:_this.dt,
	  success: function(data) {
		var count=data.count;
		var pagesnum = Math.ceil(count/nums);
		var tc = data.data;
		// var thstr;
		if(data.err_code==0) {
			if (tc.length>0) {
				Posts.html(init_html(tc));
			}
		}
		var laypageindex = laypage({
		  cont: 'biuuu_list', 
		  skin: '#fb771f',
		  pages: pagesnum, 
		  curr: 1, 
		  prev: '上一页', 
		  next: '下一页', 
		  jump: function(obj, first) {
			if(!first) {
			  _this.dt['currPage'] = obj.curr;
			  GetList("Admin-Article-list")
			}
		  }
		})
	  }
	});
}
init_html=function (data){
	var _html='';
	$.each(data,function(index, object) {
		console.log(object);
		_html += "<tr class='poid'  poid='"+object.id+"'><td align='center'>"+object.id+"</td>"+
					"<td align='center'>"+object.title+"</td>"+
					"<td align='center'>"+object.catename+"</td>"+
					"<td align='center'>"+
					'<a class="btn btn-primary btn-sm shiny" href=Admin-Article-publish-ArticleId-'+object.id+'> <i class="fa fa-edit"></i>编辑</a>'+
					"<a class='btn btn-danger btn-sm shiny delete' href='#' ><i class='fa fa-trash-o'></i>删除</a>"+
					"</td></tr>";
	
	});	
	return _html;
}
  _this.init("Admin-Article-list");

  Posts.on('click','.delete',function(event) {
  	var ArticleId = [];
  	//var adminId = .push();
  	ArticleId.push($(this).parent().parent('.poid').attr('poid'));
	layer.confirm('真的要删除吗？', {
	  btn: ['确定', '在想想'] //可以无限个按钮
	}, function(index, layero){
	 $.ajax({
	 	url: 'Admin-Article-del',
	 	type: 'post',
	 	dataType: 'json',
	 	data: {articleId: JSON.stringify(ArticleId)},
	 	success:function(data){
	 		if(data.err_code == 0){
	 			layer.msg(data.err_msg, {icon: 6});
	 			setTimeout('window.location.href="Admin-Article"',1000);
	 		}else{
	 			layer.msg(data.err_msg, {icon: 7});
	 		}
	 	}
	 })
	});
  });
  // 调用分页
  function GetList(url) {
	   $.ajax({
			type: "post",
			url: url,
			data:_this.dt,
			success: function (data) {
			var tc = data.data;
			if(data.err_code==0) {
				if (tc.length > 0) {
					  Posts.html(init_html(tc));
				}else{
					  Posts.html(data.err_msg);
				}   
			} 
			else {
				Posts.html('');
				layer.msg(data.err_msg,{icon:2});
			 }
			}
		});
	  }
})