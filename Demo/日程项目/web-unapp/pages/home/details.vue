<template>
	<view class="details">
		<view class="info">
			<view class="title">基本信息</view>
			<view class="info_row">
				<text>标题:</text>
				<text>{{info.title}}</text>
			</view>
			<view class="info_row">
				<text>分类:</text>
				<text>{{info.class_name}}</text>
			</view>
			<view class="info_row">
				<text>等级:</text>
				<text>{{info.date_leverl}}</text>
			</view>
			<view class="info_row">
				<text>日期:</text>
				<text>{{info.date}}</text>
			</view>
			<view class="info_row">
				<text>时间:</text>
				<text>{{info.date_detail}}</text>
			</view>
			<view class="info_row">
				<text>是否通知:</text>
				<text>{{info.date_notice==1?'否':'是'}}</text>
			</view>
		</view>
		<view class="desc">
			<view class="title">描述</view>
			<view class="text">{{info.content}}</view>
		</view>
		<view class="desc">
			<view class="title">图片</view>
			<!-- <view class="text">{{info.content}}</view> -->
			<image :src="info.img" mode="aspectFit" style="width: 300rpx;height: 300rpx;margin-top: 6rpx;"></image>
		</view>
		<!-- <view class="message">
			<view class="title">留言</view>
			<view class="mesage_list">
				<view v-for="k of mesageList" :key="k.id">
					<image src="/static/img/msg.png" mode="widthFix"></image> {{k.content}}
					<image src="/static/img/del.png" mode="widthFix" class="del" v-if="adminInfo.id"
						@click="handleClickDel(k)"></image>
				</view>
			</view>
		</view>
		<view class="leave_message_btn" @click="toLvMsg">留 言</view> -->
	</view>
</template>

<script>
	import {
		mapState
	} from "vuex"
	export default {
		data() {
			return {
				info: {},
			}
		},
		computed: {
		},
		onLoad(options) {
			this.info = JSON.parse(options.info)
		},
		onShow() {
			// this.loadMesageList();
		},
		methods: {
			async handleAdopt() {
				if (this.info.adoptStatus) return;
				const res = await this.$api({
					url: "applyAdopt",
					data: {
						noteId: this.info.id
					},
					type: 'post'
				})
				if (res.code == 200) {
					this.$msg("领养成功！");
					this.info.adoptStatus = 1;
				}
			},
			handleClickDel({
				id
			}) {
				uni.showModal({
					title: "确认删除该条留言吗？",
					cancelText: "取消",
					confirmText: "确认",
					success: (res) => {
						if (res.confirm) {
							this.handleDelMsg(id);
						}
					}
				})
			},
			async handleDelMsg(id) {
				const res = await this.$api({
					url: "delLeaveMessage",
					data: {
						leaveMessageId: id,
						token: this.adminInfo.token,
						adminId: this.adminInfo.id,
					},
					type: "post"
				})
				if (res.code == 200) {
					this.$msg("操作成功！");
					this.loadMesageList();
				}
			},
			async loadMesageList() {
				const res = await this.$api({
					url: "leaveMessageList",
					data: {
						questType: this.info.type,
						questId: this.info.id,
					}
				})
				if (res.code == 200) {
					this.mesageList = res.data;
				}
			},
			toLvMsg() {
				uni.navigateTo({
					url: "/pages/send/message?type=" + this.info.type + "&id=" + this.info.id
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.btn1 {
		width: 690rpx;
		height: 80rpx;
		margin: 0 auto;
		margin-top: 20rpx;
		line-height: 80rpx;
	}

	.btn2 {
		background-color: #e8e8e8 !important;
		color: #777777 !important;
	}

	.details {
		.title {
			position: relative;
			padding-left: 20rpx;

			&::after {
				content: "";
				display: block;
				width: 6rpx;
				height: 80%;
				border-radius: 3rpx;
				background-color: #FFD243;
				position: absolute;
				left: 0;
				top: 10%;
			}

			font-size: 30rpx;
			line-height: 60rpx;
		}

		.info {
			padding: 20rpx;
			background-color: #FFFFFF;

			.info_row {
				margin: 10rpx 0rpx;
				font-size: 28rpx;
				line-height: 40rpx;

				&>text:first-child {
					margin-right: 10rpx;
				}
			}
		}

		.desc {
			margin-top: 20rpx;
			padding: 20rpx;
			background-color: #FFFFFF;

			.text {
				margin-top: 20rpx;
				font-size: 26rpx;
				line-height: 40rpx;
			}
		}

		.message {
			margin-top: 20rpx;
			padding: 20rpx;
			background-color: #FFFFFF;

			.mesage_list {
				margin-top: 20rpx;

				& image {
					width: 40rpx;
					height: 40rpx;
					margin-right: 10rpx;
					position: relative;
					top: 10rpx;
				}

				&>view {
					padding: 10rpx 0rpx;
					line-height: 40rpx;
					font-size: 30rpx;
					color: #666;
					padding-right: 60rpx;
					position: relative;

					.del {
						width: 48rpx;
						height: 48rpx;
						position: absolute;
						right: 10rpx;
						top: 15rpx;
						z-index: 1;
					}
				}

				&>view:not(:last-child) {
					border-bottom: 1rpx solid #f1f1f1;
				}
			}
		}

		.leave_message_btn {
			width: 120rpx;
			height: 120rpx;
			border-radius: 50%;
			position: fixed;
			right: 10rpx;
			bottom: 10%;
			background-color: #FFD243;
			color: #FFFFFF;
			font-size: 30rpx;
			text-align: center;
			line-height: 120rpx;
			box-shadow: 0 0 5rpx 5rpx #e2e2e2;
		}
	}
</style>