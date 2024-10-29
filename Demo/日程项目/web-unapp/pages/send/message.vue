<template>
	<view class="message">
		<view class="row">
			<view class="row_lab">留言</view>
			<view class="row_val" style="height: 440rpx;">
				<textarea style="width: 100%;height: 400rpx;" v-model="form.content" placeholder-class="ph"
					placeholder="请输入内容" maxlength="500" />
			</view>
		</view>
		<view class="btn btn_submit" @click="handleLeaveMessage">发 布</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					content: "",
				},
			}
		},
		onLoad(options) {
			const {
				type,
				id
			} = options;
			this.form.questType = type;
			this.form.questId = id;
		},
		methods: {
			async handleLeaveMessage() {
				if (!this.form.content) {
					this.$msg("请输入内容")
					return;
				}
				const res = await this.$api({
					url: "leaveMessage",
					data: this.form,
					// type:"post"
				})
				if(res.code==200){
					this.$msg("发布成功！")
					uni.navigateBack()
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
	.message {
		height: 100%;
		padding: 20rpx;
		background-color: #FFFFFF;

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
			}
		}

		.btn_submit {
			margin: 80rpx auto;
			width: 600rpx;
			height: 80rpx;
			border-radius: 40rpx;
			line-height: 80rpx;
		}
	}
</style>