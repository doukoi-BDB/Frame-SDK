<template>
	<view v-if="show_box">
		<view class="popover_box" @click="handleClickModal" :class="hide?'hide_':'show_'">
			<view class="popover_box_content" :class="hide?'hide':'show'">
				<slot></slot>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: "pop_box",
		props: {
			show: {
				type: Boolean,
			},
			closeOnClickModal: {
				type: Boolean,
				default: false,
			}
		},
		data() {
			return {
				show_box:this.show,
				hide: false,
			}
		},
		onLoad() {

		},
		methods: {
			handleClickModal() {
				if (this.closeOnClickModal) {
					this.closed();
				}
			},
			closed() {
				this.hide = true;
				setTimeout(() => {
					this.$emit('update:show',false) 
					this.hide=false;
				}, 300)
			},
		},
		watch: {
			show(val) {
				this.show_box=this.show;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.popover_box {
		width: 750rpx;
		height: 100vh;
		background-color: rgba(0, 0, 0, .5);
		position: fixed;
		top: 0;
		left: 0;
		z-index: 600;
		display: flex;
		justify-content: center;
		align-items: center;

		&_content {
			display: block;
			text-align: center;
			box-sizing: border-box;
			transform-origin: 50% 50%;
			// width: 540rpx;
			// background: #FFFFFF;
			// border-radius: 25rpx;

			&_title {
				font-size: 34rpx;

				color: #2A2A2A;
				line-height: 34rpx;
			}

			&_desc {
				margin-top: 44rpx;
				font-size: 28rpx;
				color: #94918C;
				line-height: 28rpx;
			}

			&_btn {
				display: flex;
				justify-content: space-around;
				text-align: center;
				line-height: 60rpx;
				font-size: 26rpx;
				color: #2A2A2A;
				margin-top: 53rpx;

				.modal_close {
					background: #FFFFFF;
					border: 1rpx solid #DAD3CE;
					border-radius: 25rpx;
					padding: 0 45rpx;
				}

				.modal_submit {
					padding: 0 45rpx;
					background: linear-gradient(270deg, #FFCA56, #FFE985);
					border-radius: 25rpx;
				}

				.modal_center {
					width: 400rpx;
					background: linear-gradient(270deg, #FFCA56, #FFE985);
					border-radius: 25rpx;
				}
			}
		}

		.show {
			transform: scale(1);
			animation: show 0.3s linear;
			opacity: 1;
		}

		.hide {
			transform: scale(0);
			animation: hide 0.3s linear;
			opacity: 0;
		}
	}



	@keyframes show {
		0% {
			transform: scale(0);
			opacity: 0;
		}

		100% {
			transform: scale(1);
			opacity: 1;
		}
	}

	@keyframes hide {
		0% {
			transform: scale(1);
			opacity: 1;
		}

		100% {
			transform: scale(0);
			opacity: 0;
		}
	}

	.show_ {
		animation: show_ 0.3s linear;
		opacity: 1;
	}

	.hide_ {
		animation: hide_ 0.3s linear;
		opacity: 0;
	}

	@keyframes show_ {
		0% {
			opacity: 0;
		}

		100% {
			opacity: 1;
		}
	}

	@keyframes hide_ {
		0% {
			opacity: 1;
		}

		100% {
			opacity: 0;
		}
	}
</style>