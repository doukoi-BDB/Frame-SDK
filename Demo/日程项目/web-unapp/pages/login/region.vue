<template>
	<view class="form_page">
		<view class="box">
			<view class="row">
				<view class="row_lab">账 号</view>
				<view class="row_val">
					<input type="text" v-model="form.user_num" placeholder-class="ph" placeholder="请输入" :disabled="hasLogin"/>
				</view>
			</view>
			<view class="row">
				<view class="row_lab">密 码</view>
				<view class="row_val">
					<input type="text" v-model="form.user_pwd" password placeholder-class="ph" placeholder="请输入" />
				</view>
			</view>
			<view class="row">
				<view class="row_lab">用户名</view>
				<view class="row_val">
					<input type="text" v-model="form.user_name" placeholder-class="ph" placeholder="请输入" />
				</view>
			</view>
			<view class="row">
				<view class="row_lab">性 别</view>
				<view class="row_val_">
					<view @click="form.user_sex=1">
						<image :src="`/static/img/tickp${form.user_sex==1?'':'_'}.png`" mode="widthFix"></image>
						<text>男</text>
					</view>
					<view @click="form.user_sex=2">
						<image :src="`/static/img/tickp${form.user_sex==2?'':'_'}.png`" mode="widthFix"></image>
						<text>女</text>
					</view>
				</view>
			</view>
			<view class="row">
				<view class="row_lab">简 介</view>
				<view class="row_val" style="height: 240rpx;">
					<textarea style="width: 100%;height: 200rpx;" v-model="form.user_content" placeholder-class="ph"
						placeholder="请输入" maxlength="500" />
				</view>
			</view>
		</view>

		<view class="btn_box">
			<view>
				<view class="btn" style="width: 500rpx;height: 80rpx;line-height: 80rpx;border-radius: 40rpx;"
					@click="handleRegion">{{btnText}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		mapState,
		mapGetters
	} from "vuex"
	export default {
		data() {
			return {
				form: {
					user_num: "",
					user_pwd: "",
					user_name: "",
					user_sex: 1,
					user_content: "",
				},
				isLoading: false,
				btnText: "注 册",
			}
		},
		computed: {
			...mapState(["userInfo"]),
			...mapGetters(["hasLogin"])
		},
		onLoad(options) {
			if (this.hasLogin) {
				uni.setNavigationBarTitle({
					title: "个人信息"
				})
				this.form = {
					...this.userInfo
				}
				this.btnText = "提 交"
			}
		},
		methods: {
			//发布信息
			async handleRegion() {
				if (!this.hasLogin) {
					if (!this.form.user_num) {
						this.$msg("请输入账号")
						return;
					}
					if (!this.form.user_pwd) {
						this.$msg("请输入密码")
						return;
					}
				}
				if (!this.form.user_name) {
					this.$msg("请输入用户名")
					return;
				}
				if (this.isLoading) return;
				this.isLoading = true;
				uni.showLoading({
					title: this.hasLogin ? "正在提交..." : "注册中..."
				})
				const res = await this.$api({
					url: this.hasLogin ? "updateUserInfo" : "region",
					data: this.form,
				})
				uni.hideLoading()
				if (res.code == 200) {
					uni.showToast({
						icon: 'success',
						title: this.hasLogin ? "修改成功" : "注册成功"
					})
					setTimeout(() => {
						if (this.hasLogin) {
							this.$store.dispatch("getUserInfo")
						}
						uni.navigateBack()
					}, 500)
				} else {
					uni.showToast({
						icon: 'fail',
						title: res.msg
					})
				}
			},
		}
	}
</script>

<style lang="scss" scoped>
	.form_page {
		padding-top: 20rpx;

		.box {
			width: 690rpx;
			margin: 0 auto;
			padding: 20rpx;
			border-radius: 20rpx;
			background-color: #FFF;

			.row {
				margin-bottom: 20rpx;

				.row_lab {
					position: relative;
					padding-left: 20rpx;

					&::after {
						content: "";
						display: block;
						width: 4rpx;
						height: 100%;
						border-radius: 2rpx;
						background-color: #FFD243;
						position: absolute;
						left: 0;
						top: 0;
					}

					font-size: 30rpx;
					line-height: 50rpx;
					margin-bottom: 20rpx;
				}

				.row_val {
					height: 80rpx;
					background-color: #F6F7FB;
					border-radius: 10rpx;
					padding: 0rpx 20rpx;
					display: flex;
					align-items: center;

					&>input {
						flex: 1;
					}
				}

				.row_val_ {
					display: flex;
					align-items: center;
					height: 80rpx;

					& image {
						width: 30rpx;
						height: 30rpx;
						margin-right: 6rpx;
					}

					&>view {
						margin-right: 60rpx;
					}
				}
			}
		}

		.btn_box {
			height: 120rpx;

			&>view {
				position: fixed;
				bottom: 0;
				left: 0;
				z-index: 111;
				background-color: #FFF;
				width: 750rpx;
				height: 110rpx;
				display: flex;
				align-items: center;
				justify-content: center;
			}
		}

		.image {
			width: 150rpx;
			height: 150rpx;
			border-radius: 20rpx;
		}

		.upload_image {
			width: 150rpx;
			height: 150rpx;
			border: 1rpx solid #999;
			border-radius: 20rpx;
			position: relative;

			&::after {
				content: "";
				display: block;
				width: 4rpx;
				height: 60rpx;
				background-color: #999;
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
			}

			&::before {
				content: "";
				display: block;
				width: 60rpx;
				height: 4rpx;
				background-color: #999;
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
			}
		}
	}
</style>