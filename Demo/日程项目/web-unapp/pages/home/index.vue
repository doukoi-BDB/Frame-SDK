<template>
	<scroll-view class="home_page" scroll-y @scrolltolower="()=>loadList()">
		<view class="tab">
			<view class="tab_item" :class="actTab==i?'tab_act':''" v-for="(k,i) of tablist" :key="i"
				@click="handleClickTab(i)">{{k}}
			</view>
		</view>
		<view v-if="actTab==0">
			<view class="date">
				{{nowDate}} 星期{{weekTitles[day]}}
			</view>
			<view class="desc">{{signlist[index]}}</view>
		</view>
		<chenmushanWeekCalendar v-if="actTab==1" :defaultDate="defaultDate" @changeDate="change">
		</chenmushanWeekCalendar>
		<gwbqCalendar v-if="actTab==2" :date="act_date" :insert="true" :lunar="true" @change="change"
			@monthSwitch="monthSwitch" />
        <view style="height: 4rpx;"></view>
		<messageItem v-for="k of list" :item="k" :key="k.id" @del="handleDel"></messageItem>
		<view class="send_btn" @click="handleSend">
			<image src="/static/send.png" mode="widthFix"></image>
		</view>
		<view class="noData" v-if="!list.length">暂无数据</view>
		<popBox :show.sync="show" :closeOnClickModal="true">
			<view class="pop_box">
				<view class="title">提示</view>
				<view class="text" v-for="(k,i) of notices" :key="i">{{i+1}}、{{k}}</view>
				<view class="btn" style="width: 400rpx;margin: 0 auto;margin-top: 40rpx;">知道了</view>
			</view>
		</popBox>
	</scroll-view>
</template>

<script>
	import messageItem from "@/components/message-item/index.vue"
	import gwbqCalendar from "@/components/gwbq-calendar/gwbq-calendar.vue"
	import chenmushanWeekCalendar from "@/components/chenmushan-weekCalendar/chenmushan-weekCalendar.vue"
	import popBox from "@/components/pop-box/pop-box.vue"
	import {
		mapState
	} from "vuex"
	export default {
		components: {
			messageItem,
			gwbqCalendar,
			chenmushanWeekCalendar,
			popBox
		},
		data() {
			return {
				tablist: ['当日视图', '每周视图', '每月视图'],
				weekTitles: ['日', '一', '二', '三', '四', '五', '六'],
				list: [],
				actTab: 0,
				signlist: [
					"我不怕受伤，只怕努力最后没被谁记得",
					"繁华落尽谁许我一世安好",
					"幸福是可以共享的，孤独永远是自己的",
					"一入人事深似海，从此节操为路人",
					"人生自是有情痴，莫叫生死作相思",
					"最美的人鱼藏在深海，最深爱你的人值得等待",
					"越是夜深人静越就孤单相随",
					"像一块铁却是冰心里住着风亦冷自清",
					"能够在最美好的时光与你相遇、这就是幸福",
					"遇见你三生有幸，此生还请多多关照",
					"谨以此生陪你白头谨以此生等你回首",
					"纵马一生少不得故作深沉"
				],
				index: 0,
				act_date: "", //当前展示日期
				nowDate: "", //当日日期
				day: 0,
				page: 1,
				size: 10,
				isLoading: false,
				isMore: true,
				show:false,
				notices:[],
			}
		},
		computed: {
			...mapState(["userInfo"]),
			defaultDate() {
				if (this.act_date) {
					return new Date(this.act_date).getTime()
				}
				return new Date().getTime()
			}
		},
		onLoad(options) {
			let now = new Date();
			let nowDate = this.$moment(now, "YYYY-MM-DD");
			this.act_date = nowDate;
			this.nowDate = nowDate;
			this.day = now.getDay();
			this.index = parseInt(Math.random() * 12)
			// this.loadList(true)
		},
		onShow() {
			// this.loadList();
			this.loadList(true)
			this.handleGetNotice();
		},
		methods: {
			async handleGetNotice(){
				const res=await this.$api({
					url:'notice'
				})
				if(res.code==200){
					this.notices=res.data;
					this.show=true;
				}
			},
			handleDel(item){
				uni.showModal({
					title:"提示",
					content:"确认是否删除？",
					cancelText:"否",
					confirmText:"是",
					success:async (r) => {
						const {confirm,cancel}=r;
						if(confirm){//确认删除
							const res=await this.$api({
								url:"delContent",
								data:{
									id:item.id,
									uid:uni.getStorageSync('userId')
								}
							})
							if(res.code==200){
								this.$msg("删除成功！")
								this.loadList(true);
							}
						}
					}
				})
			},
			handleSend() {
				uni.navigateTo({
					url: "/pages/send/form?date=" + this.nowDate
				})
			},
			change(e) {
				const {
					fulldate
				} = e;
				console.log(e);
			},
			monthSwitch(e) {
				console.log(e);
			},
			handleClickTab(val) {
				this.actTab = val;
				// this.loadList(true)
			},
			async loadList(rest) {
				if (rest) {
					this.page = 1;
					this.list = [];
					this.isMore = true;
				}
				if (!this.isMore) {
					this.$msg("没有更多了")
					return
				}
				if (this.isLoading) return;
				this.isLoading = true;
				const res = await this.$api({
					url: "getContent",
					data: {
						page: this.page,
						size: this.size,
						uid: this.userInfo.user_num == "admin" ? "" : uni.getStorageSync('userId'),
					}
				})
				this.isLoading = false;
				if (res.code == 200) {
					this.list = this.list.concat(res.data);
					this.isMore = res.data.length == this.size;
					this.page++
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
	.pop_box{
		width: 600rpx;
		padding: 20rpx;
		border-radius: 20rpx;
		background-color: #FFFFFF;
		.title{
			font-size: 34rpx;
			font-weight: 500;
			line-height: 60rpx;
			text-align: center;
			color: #222222;
		}
		.text{
			font-size: 26rpx;
			line-height: 40rpx;
			color: #666666;
		}
	}
	
	.home_page {
		height: 100%;
		overflow: hidden;

		.send_btn {
			position: fixed;
			bottom: 200rpx;
			right: 40rpx;
			width: 140rpx;
			height: 140rpx;
			background-color: #FFF;
			overflow: hidden;
			border-radius: 50%;
			padding-top: 15rpx;
			z-index: 9;

			&>image {
				width: 110rpx;
				height: 110rpx;
				display: block;
				margin: 0 auto;
			}
		}

		.date {
			font-size: 34rpx;
			font-weight: bold;
			color: #FFD243;
			pointer-events: none;
			text-align: center;
			line-height: 60rpx;
			background-color: #FFFFFF;
		}

		.desc {
			padding: 0rpx 20rpx;
			font-size: 26rpx;
			margin-top: 2rpx;
			line-height: 60rpx;
			color: #FFD243;
			background-color: #FFFFFF;
		}

		.tab {
			width: 750rpx;
			height: 90rpx;
			background-color: #FFD243;
			display: flex;
			justify-content: space-around;
			align-items: center;

			.tab_item {
				width: 30%;
				font-size: 28rpx;
				line-height: 60rpx;
				text-align: center;
				color: #FFFFFF;
			}

			.tab_act {
				font-size: 34rpx;
				font-weight: bold;
				color: #FFD243;
				background-color: #FFF;
				border-radius: 6rpx;
				position: relative;

				&::after {
					content: "";
					display: block;
					width: 100%;
					height: 5rpx;
					border-radius: 4rpx;
					background-color: #FFD243;
					position: absolute;
					bottom: -4rpx;
					left: 0;
				}
			}
		}
	}
</style>