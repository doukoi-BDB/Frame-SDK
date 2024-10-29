<template>
	<view class="admin">
		<view class="box">
			<view class="row">
				<view class="row_lab">账号:</view>
				<view class="row_val">
					<input type="text" v-model="form.account" placeholder="请输入账号" placeholder-class="ph" />
				</view>
			</view>
			<view class="row">
				<view class="row_lab">密码:</view>
				<view class="row_val">
					<input type="password" v-model="form.password" placeholder="请输入密码" placeholder-class="ph" />
				</view>
			</view>
		</view>

		<view class="btn login_btn" @click="login">登 录</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					account: "admin",
					password: "1234567890",
				},
			}
		},
		onLoad(options) {

		},
		methods: {
			async login() {
				const res = await this.$api({
					url: "adminlogin",
					data: this.form,
					type: "post"
				})
				if (res.code == 200) {
					const {
						id,
						token
					} = res.data;
					const adminInfo = {
						id: id,
						token: token
					};
					uni.setStorageSync("adminInfo", adminInfo);
					this.$store.commit("setState", {
						key: "adminInfo",
						val: adminInfo
					})
					uni.redirectTo({
						url: '/pages/admin/list'
					})
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
	.admin {
		height: 100%;
		padding-top: 50rpx;

		.box {
			width: 690rpx;
			margin: 0 auto;
			background-color: #FFFFFF;
			padding: 20rpx;
			border-radius: 10rpx;
		}

		.row {
			margin-bottom: 20rpx;

			.row_lab {
				position: relative;
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
		}

		.login_btn {
			width: 600rpx;
			height: 80rpx;
			line-height: 80rpx;
			border-radius: 40rpx;
			margin: 0 auto;
			margin-top: 140rpx;
		}
	}
</style>