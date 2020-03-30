/*
 * @Author: isboyjc
 * @Date: 2020-03-26 21:21:15
 * @LastEditors: isboyjc
 * @LastEditTime: 2020-03-26 21:41:36
 * @Description: this is a demo
 */
const { Wechaty } = require("wechaty")
const { PuppetPadplus } = require("wechaty-puppet-padplus")
const QrcodeTerminal = require("qrcode-terminal")

const token = ""

const puppet = new PuppetPadplus({
  token
})

const name = "没得感情的机器人"

const bot = new Wechaty({
  puppet,
  name // generate xxxx.memory-card.json and save login data for the next login
})

bot
  .on("scan", qrcode => {
    console.log(qrcode)
    QrcodeTerminal.generate(qrcode, {
      small: true
    })
  })
  .on("message", msg => {
    console.log(`msg : ${msg}`)
  })
  .start()
