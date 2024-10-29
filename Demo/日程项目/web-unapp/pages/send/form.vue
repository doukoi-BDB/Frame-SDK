<template>
	<view class="form_page">
		<view class="box">
			<view class="row">
				<view class="row_lab">标题</view>
				<view class="row_val">
					<input type="text" v-model="form.title" placeholder-class="ph" placeholder="请输入" />
				</view>
			</view>
			<view class="row">
				<view class="row_lab">分类名称</view>
				<picker @change="bindPickerChange" :value="index" :range="class_list" style="width: 100%;height: 100%;">
					<view class="row_val">{{class_list[index]}}</view>
				</picker>
			</view>
			<view class="row">
				<view class="row_lab">时间-年月日</view>
				<picker @change="bindPickerChangeDate" mode="date" style="width: 100%;height: 100%;">
					<view class="row_val">{{form.date||"请选择"}}</view>
				</picker>
			</view>
			<view class="row">
				<view class="row_lab">时间-时分秒</view>
				<picker @change="bindPickerChangeTime" mode="time" style="width: 100%;height: 100%;">
					<view class="row_val">{{form.date_detail ||"请选择"}}</view>
				</picker>
			</view>
			<view class="row">
				<view class="row_lab">是否通知</view>
				<view class="row_val_">
					<view @click="form.date_notice=1">
						<image :src="`/static/img/tickp${form.date_notice==1?'':'_'}.png`" mode="widthFix"></image>
						<text>否</text>
					</view>
					<view @click="form.date_notice=2">
						<image :src="`/static/img/tickp${form.date_notice==2?'':'_'}.png`" mode="widthFix"></image>
						<text>是</text>
					</view>
				</view>
			</view>
			<view class="row">
				<view class="row_lab">日程等级</view>
				<picker @change="bindPickerChange1" :value="index1" :range="class_list1"
					style="width: 100%;height: 100%;">
					<view class="row_val">{{class_list1[index1]}}</view>
				</picker>
			</view>
			<view class="row">
				<view class="row_lab">图片</view>
				<view class="row_val__">
					<image :src="form.img" mode="aspectFit" class="image" v-if="form.img"></image>
					<view class="upload_image" v-else @click="handleUploadImg"></view>
				</view>
			</view>
			<view class="row">
				<view class="row_lab">内容</view>
				<view class="row_val" style="height: 240rpx;">
					<textarea style="width: 100%;height: 200rpx;" v-model="form.content" placeholder-class="ph"
						placeholder="请输入" maxlength="500" />
				</view>
			</view>
		</view>

		<view class="btn_box">
			<view>
				<view class="btn" style="width: 500rpx;height: 80rpx;line-height: 80rpx;border-radius: 40rpx;"
					@click="handleSend">发 布</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					title: "",
					content: "",
					date: "",
					date_detail: "",
					class_name: "工作",
					date_leverl: "普通", 
					date_notice: 1,
					img: "",
					uid: uni.getStorageSync('userId'),
				},
				isLoading: false,
				index: 0,
				class_list: ['工作', '家庭', '健康', '学习', '休闲娱乐'],
				index1: 0,
				class_list1: ['普通', '中等', '严重'],
			}
		},
		onLoad(options) {
			this.form.date = options.date;
		},
		methods: {
			//时间
			bindPickerChangeTime(e) {
				const {
					value
				} = e.detail;
				this.form.date_detail = value;
			},
			//日期
			bindPickerChangeDate(e) {
				const {
					value
				} = e.detail;
				this.form.date = value;
			},
			//分类
			bindPickerChange(e) {
				const {
					value
				} = e.detail;
				this.index = value;
				this.form.class_name = this.class_list[value];
			},
			//日程等级
			bindPickerChange1(e) {
				const {
					value
				} = e.detail;
				this.index1 = value;
				this.form.date_leverl = this.class_list1[value];
			},
			handleUploadImg() {
				uni.chooseImage({
					count: 1,
					sizeType: ['compressed'],
					sourceType: ['album', 'camera'],
					success: async (chooseImageRes) => {
						uni.showLoading({
							title: "上传中...",
						})
						this.form.img = chooseImageRes.tempFilePaths[0];
						uni.hideLoading()
					},
					fail: (err) => {
						console.log(err);
					}
				});
			},
			//发布信息
			async handleSend() {
				if (!this.form.title) {
					this.$msg("请输入标题")
					return;
				}
				if (!this.form.content) {
					this.$msg("请输入内容")
					return;
				}
				if (!this.form.class_name) {
					this.$msg("请选择分类")
					return;
				}
				if (!this.form.date_leverl) {
					this.$msg("请选择等级")
					return;
				}
				if (this.isLoading) return;
				this.isLoading = true;
				const res = await this.$api({
					url: "sendContent",
					data: this.form,
				})
				if (res.code == 200) {
					this.$msg("发布成功！")
					setTimeout(() => {
						uni.switchTab({
							url: "/pages/home/index"
						})
					}, 500)
				}
			},
		}
	}
</script>
<style>
	page,uni-page-body{
		height: 100%;
	}
</style>
<style lang="scss" scoped>
	.form_page {
		padding-top: 20rpx;
        height: 100%;
		overflow: hidden;
		overflow-y: scroll;
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