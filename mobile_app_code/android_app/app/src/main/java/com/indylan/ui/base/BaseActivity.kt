package com.indylan.ui.base

import android.content.Intent
import android.os.Build
import android.os.Bundle
import android.view.MenuItem
import android.view.View
import android.view.WindowManager
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.res.ResourcesCompat
import com.google.android.material.snackbar.Snackbar
import com.indylan.R
import com.indylan.common.extensions.goneView
import com.indylan.common.extensions.hideView
import com.indylan.common.extensions.setSystemUiLightStatusBar
import com.indylan.common.extensions.showView
import com.indylan.databinding.LayoutToolbarBinding
import com.indylan.ui.auth.AuthenticationActivity
import com.indylan.ui.home.HomeActivity

abstract class BaseActivity : AppCompatActivity() {

    abstract fun findContentView(): View?

    abstract fun toolbar(): LayoutToolbarBinding?

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        findContentView()?.let {
            setContentView(it)
        }
    }

    fun setTitle(
        title: String,
        subtitle: String?,
        showBack: Boolean,
        showProfile: Boolean,
        showLogout: Boolean,
        profileCallback: (Unit) -> (Unit),
        logoutCallback: (Unit) -> (Unit)
    ) {
        toolbar()?.let {

            it.textViewTitle.text = title

            if (subtitle != null) {
                it.textViewSubTitle.showView()
                it.textViewSubTitle.text = subtitle
            } else {
                it.textViewSubTitle.goneView()
            }

            if (showBack) {
                it.imageViewBack.showView()
                it.imageViewBack.setOnClickListener {
                    onBackPressed()
                }
            } else {
                it.imageViewBack.hideView()
            }

            when {
                showProfile -> {
                    it.imageViewProfile.showView()
                    it.imageViewProfile.setOnClickListener {
                        profileCallback.invoke(Unit)
                    }
                    it.imageViewLogout.goneView()
                }
                showLogout -> {
                    it.imageViewProfile.goneView()
                    it.imageViewLogout.showView()
                    it.imageViewLogout.setOnClickListener {
                        logoutCallback.invoke(Unit)
                    }
                }
                else -> {
                    it.imageViewLogout.hideView()
                    it.imageViewProfile.goneView()
                }
            }
        }
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        if (item.itemId == android.R.id.home) {
            onBackPressed()
            return true
        }
        return super.onOptionsItemSelected(item)
    }

    fun showMessage(message: String?, view: View = findViewById(android.R.id.content)) {
        message?.let {
            val snackBar = Snackbar.make(view, it, Snackbar.LENGTH_LONG)
            //snackBar.setAnchorView(R.id.stripsView)
            snackBar.show()
        }
    }

    fun unauthorize() {
        //preferenceStorage.user = null
        val intent = Intent(this, AuthenticationActivity::class.java)
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK or Intent.FLAG_ACTIVITY_NEW_TASK)
        startActivity(intent)
    }

    fun authorize() {
        val intent = Intent(this, HomeActivity::class.java)
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK or Intent.FLAG_ACTIVITY_NEW_TASK)
        startActivity(intent)
    }

    fun setAuthView() {
        toolbar()?.layoutToolbar?.goneView()
        window?.setBackgroundDrawableResource(R.drawable.bg_normal)
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS)
            //window.statusBarColor = getThemeColor(android.R.attr.colorBackground, android.R.color.black)
        }
        setSystemUiLightStatusBar(true)
    }

    fun setHomeView() {
        toolbar()?.layoutToolbar?.showView()
        window?.setBackgroundDrawableResource(R.drawable.bg_primary)
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS)
            window.statusBarColor =
                ResourcesCompat.getColor(resources, android.R.color.transparent, null)
        }
        setSystemUiLightStatusBar(false)
    }
}