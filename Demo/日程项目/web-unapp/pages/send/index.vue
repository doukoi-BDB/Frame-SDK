<template>
	<view class="send_page">
		<view class="box">
			<view class="left" style="background-color: #FFCF0D;">
				<image src="/static/send/icon_1.png" mode="widthFix"></image>
			</view>
			<view class="right">
				<view class="title">流浪动物</view>
				<view class="desc">小动物寻找新家</view>
				<view class="btn" @click="handleClickSend(1)">发 布</view>
			</view>
		</view>
		<view class="box">
			<view class="left" style="background-color: #92D9F5;">
				<image src="/static/send/icon_2.png" mode="widthFix"></image>
			</view>
			<view class="right">
				<view class="title">救助任务</view>
				<view class="desc">时间就是生命</view>
				<view class="btn" @click="handleClickSend(2)">发 布</view>
			</view>
		</view>
	<!-- 	<view class="box">
			<view class="left" style="background-color: #B8EBB0;">
				<image src="/static/send/icon_3.png" mode="widthFix"></image>
			</view>
			<view class="right">
				<view class="title">寻主信息</view>
				<view class="desc">为小动物寻找主人</view>
				<view class="btn">发 布</view>
			</view>
		</view> -->
	</view>
</template>

<script>
	import {mapState,mapGetters} from "vuex"
	export default {
		data() {
			return {

			}
		},
		computed:{
			...mapGetters(["hasLogin"]),
		},
		onLoad(options) {

		},
		methods: {
             handleClickSend(type){
				if(!this.hasLogin){
					uni.navigateTo({
						url:"/pages/login/index"
					})
				}
				uni.navigateTo({
					url:"/pages/send/form?type="+type
				})
			 },
		}
	}
</script>

<style lang="scss" scoped>
	.send_page {
		padding: 30rpx;
		.box {
			width: 690rpx;
			padding: 30rpx 40rpx;
			margin-bottom: 30rpx;
			border-radius: 20rpx;
			background-color: #FFFFFF;
			display: flex;
			justify-content: space-between;

			.left {
				width: 200rpx;
				height: 200rpx;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;

				&>image {
					width: 120rpx;
					height: 120rpx;
				}
			}

			.right {
				width: 40%;
				display: flex;
				flex-direction: column;
				align-items: center;
				padding-top: 30rpx;

				.title {
					font-size: 30rpx;
					color: #333333;
					margin-bottom: 10rpx;
				}

				.desc {
					font-size: 24rpx;
					color: #777777;
					margin-bottom: 20rpx;
				}

				.btn {
					width: 200rpx;
				}
			}
		}

	}
</style>