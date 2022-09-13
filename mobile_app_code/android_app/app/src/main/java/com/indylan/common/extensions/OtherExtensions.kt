package com.indylan.common.extensions

import android.content.ActivityNotFoundException
import android.content.Context
import android.content.Intent
import android.net.Uri

fun Context.call(phone: String?) {
    val intent = Intent(Intent.ACTION_DIAL)
    intent.data = Uri.parse("tel:$phone")
    startActivity(intent)
}

fun Context.mail(email: String?) {
    val intent = Intent(Intent.ACTION_VIEW)
    intent.data = Uri.parse("mailto:$email")
    startActivity(intent)
}

fun Context.openBrowser(url: String?) {
    url?.let {
        val intent = Intent(Intent.ACTION_VIEW)
        if (!url.startsWith("http://") && !url.startsWith("https://")) {
            intent.data = Uri.parse("http://$url")
        } else {
            intent.data = Uri.parse(url)
        }
        startActivity(intent)
    }
}

fun Context.shareText(text: String) {
    val sharingIntent = Intent(Intent.ACTION_SEND)
    sharingIntent.type = "text/plain"
    //sharingIntent.putExtra(Intent.EXTRA_SUBJECT, "Subject Here")
    sharingIntent.putExtra(Intent.EXTRA_TEXT, text)
    startActivity(Intent.createChooser(sharingIntent, "Share"))
}

fun Context.openPlayStore() {
    try {
        startActivity(
            Intent(
                Intent.ACTION_VIEW,
                Uri.parse("https://play.google.com/store/apps/details?id=$packageName")
            )
        )
    } catch (e: ActivityNotFoundException) {
        e.printStackTrace()
    }
}