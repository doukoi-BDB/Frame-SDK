<template>
	<view class="login_page">
		<view class="title">
			欢迎登录海边日程
		</view>
		<view class="box">
			<!-- <image src="/static/logo.png" mode="widthFix" class="logo"></image> -->
			<view class="form_box">
				<view class="row">
					<image src="/static/login/icon_1.png" mode="widthFix"></image>
					<input type="text" v-model="form.user_num" placeholder="账号" />
				</view>
				<view class="row">
					<image src="/static/login/icon_2.png" mode="widthFix"></image>
					<input type="safe-password" password v-model="form.user_pwd" placeholder="密码" />
				</view>
			</view>
			<view class="rlu" @click="isRlu=!isRlu">
				<image :src="`/static/img/tick${isRlu?'':'_'}.png`" mode="widthFix" class="rlu_icon"></image>
				<view class="rlu_text">
					点击授权登录即表示同意 <text @click.stop="toUrl" style="color:#FFD243 ;">《用户隐私服务协议》</text>
				</view>
			</view>
			<view class="btn login_btn" @click="login">校园登录</view>
			<view class="btn region_btn" @click="region">注 册</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				isRlu: false,
				form:{},
			}
		},
		onLoad(options) {

		},
		methods: {
			async login() {
				if (!this.isRlu) {
					this.$msg("请勾选并阅读用户隐私服务协议")
					return;
				}
				uni.showLoading({
					title: "登录中..."
				})
				const res = await this.$api({
					url: "login",
					data: this.form,
				})
				uni.hideLoading()
				if (res.code == 200) {
					this.$store.commit("setUserId", res.data)
					uni.switchTab({
						url: "/pages/home/index"
					})
				}

			},
			region(){
				uni.navigateTo({
					url:"/pages/login/region"
				})
			},
			toUrl() {

			}
		}
	}
</script>

<style lang="scss" scoped>
	.login_page {
		height: 100%;
		// background: linear-gradient(0deg, #F6FEFA, #FFD243);
		// background-color: #ffffff;
		position: relative;
        .title{
			font-size: 40rpx;
			padding-left: 20rpx;
			padding-top: 80rpx;
			font-weight: 400;
			color: #666;
			// color: #FFD243;
		}
		.logo{
			width: 180rpx;
			height:180rpx;
			margin: 0 auto;
			margin-bottom: 40rpx;
		}
		.box {
			width: 600rpx;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			display: flex;
			flex-direction: column;
			align-items: center;

			.login_btn {
				width: 500rpx;
				height: 80rpx;
				border-radius: 40rpx;
				line-height: 80rpx;
				font-size: 34rpx;
			}

			.region_btn {
				width: 500rpx;
				height: 80rpx;
				border-radius: 40rpx;
				line-height: 80rpx;
				font-size: 34rpx;
				margin-top: 20rpx;
				background-color: #ffffff;
				color: #FFD243;
				box-shadow: 0rpx 0rpx 2rpx 2rpx #FFD243;
			}

			.rlu {
				display: flex;
				align-items: center;
				font-size: 24rpx;
				margin-bottom: 30rpx;

				.rlu_icon {
					width: 28rpx;
					height: 28rpx;
					margin-right: 10rpx;
				}

				.rlu_text {
					line-height: 30rpx;
				}
			}

			.form_box {
				width: 600rpx;

				.row {
					height: 80rpx;
					border-radius: 10rpx;
					display: flex;
					padding: 0 20rpx;
					align-items: center;
					margin-bottom: 20rpx;
					background-color: #ffffff;

					&>image {
						width: 48rpx;
						height: 48rpx;
						margin-right: 20rpx;
					}

					&>input {
						flex: 1;
					}
				}
			}
		}
	}
</style>