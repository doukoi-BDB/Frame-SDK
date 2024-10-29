<template>
	<view class="message" @click="toDetails">
	   <!-- <image :src="item.img" mode="scaleToFill" class="avt"></image> -->
	   <view class="info">
		   <view class="name">{{item.title}}   </view>
		 <!--  <view class="price_">
			   <text>分类: {{item.class_name}}</text>
			   <text>等级: {{item.date_leverl}}</text>
		  </view> -->
		 <!--  <view class="tags">
			   <view :style="{backgroundColor:item.date_leverl=='中等'?'#FFD243':item.date_leverl=='严重'?'#FF7575':'#F6F7FB'}">{{item.date_leverl}}</view>
		   </view> -->
		   <view class="desc" style="margin-bottom: 20rpx;">
			   {{item.content}}
		   </view>
		   <view class="price_">
			   作者: {{item.author}}
		   </view>
		   <view class="price_">
			   时间: {{item.create_time}}
		   </view>
	   </view>
	   <image src="/static/del.png" mode="widthFix" class="del_icon" @click.stop="handleDel"></image>
	</view>
</template>

<script>
	export default {
		props:["item"],
		data() {
			return {
				
			}
		},
		mounted() {
			
		},
		methods: {
			 toDetails(){
				 uni.navigateTo({
				 	url:"/pages/my/details?info="+JSON.stringify(this.item)
				 })
			 },
			 handleDel(){
				 this.$emit("del",this.item)
			 }
		},
	}
</script>

<style lang="scss" scoped>
	.message{
		width: 750rpx;
		padding: 20rpx;
		border-bottom: 1rpx solid #E8E8E8;
		background-color: #FFFFFF;
		display: flex;
		position: relative;
		.avt{
			width: 180rpx;
			height: 180rpx;
			border-radius: 10rpx;
			display: block;
		}
		.del_icon{
			width: 48rpx;
			height: 48rpx;
			position: absolute;
			top: 20rpx;
			right: 20rpx;
		}
		.info{
			flex: 1;
			padding-left: 20rpx;
			.name{
				font-weight: 400rpx;
				line-height: 50rpx;
				font-size: 32rpx;
			}
			.tags{
				display: flex;
				height:40rpx;
				margin: 10rpx auto;
				&>view{
					padding: 0rpx 20rpx;
					border-radius: 6rpx;
					font-size: 26rpx;
					line-height: 40rpx;
					background-color: #F6F7FB;
					color: #666666;
					margin-right: 10rpx;
				}
			}
			.desc{
				height: 60rpx;
				margin-top: 10rpx;
				font-size: 28rpx;
				line-height: 30rpx;
				overflow: hidden;
				text-overflow: ellipsis;
			}
			.price{
				margin-top: 10rpx;
				font-size: 28rpx;
				line-height: 30rpx;
				color: #e1e1e1;
			}
			.price_{
				padding-right: 100rpx;
				font-size: 24rpx;
				line-height: 40rpx;
				color: #666666;
				display: flex;
				justify-content: space-between;
			}
		}
	}
</style>