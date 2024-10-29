import {
	BAES_URL,
	service,
} from "./config.js"
export const api = options => {
	//suffix 后缀
	const {
		type = "GET", data = {}, url, header = {}, suffix = "", loading = false, needUserId = true
	} = options;

	return new Promise(function(resolve, reject) {
		const Request_URL = BAES_URL + service[url] //正式服
		if (loading) {
			uni.showLoading({
				title: "加载中...",
			})
		}
		const userId = uni.getStorageSync('userId');
		if (userId && !data.userId && needUserId) {
			data.userId = userId;
			data.uid = userId;
		}
		uni.request({
			url: suffix ? Request_URL + suffix : Request_URL,
			data: data,
			method: type,
			header: {
				'content-type': 'application/json',
				...header
			},
			success: res => {
				const {
					code,
					msg
				} = res.data;

				switch (code) {
					case 401:
						break;
					default:
						// if (msg && code != 200) {
						// 	uni.showToast({
						// 		title: msg,
						// 		icon: "none"
						// 	})
						// }
						break;
				}
				resolve(res.data)
			},
			fail: err => {
				console.log(err)
			},
			complete() {
				if (loading) {
					uni.hideLoading()
				}
			}
		})
	})
}