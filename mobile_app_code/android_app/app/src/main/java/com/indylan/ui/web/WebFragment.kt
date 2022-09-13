package com.indylan.ui.web

import android.annotation.SuppressLint
import android.content.res.Configuration
import android.graphics.Bitmap
import android.os.Build
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.webkit.WebSettings
import android.webkit.WebView
import android.webkit.WebViewClient
import androidx.fragment.app.viewModels
import com.indylan.common.extensions.goneView
import com.indylan.common.extensions.showView
import com.indylan.databinding.FragmentWebBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import com.tuyenmonkey.mkloader.MKLoader
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class WebFragment : BaseFragment() {

    private val viewModel: WebViewModel by viewModels()
    private lateinit var binding: FragmentWebBinding
    private val title by lazy {
        WebFragmentArgs.fromBundle(requireArguments()).title
    }
    private val url by lazy {
        WebFragmentArgs.fromBundle(requireArguments()).url
    }

    override fun getViewModel(): BaseViewModel = viewModel

    override fun onBackPress(): Boolean = true

    override fun getLoadingView(): LayoutProgressBinding? = binding.includeProgress

    override fun getErrorView(): LayoutErrorBinding? = null

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentWebBinding.inflate(inflater, container, false).apply {

        }
        return binding.root
    }

    @SuppressLint("SetJavaScriptEnabled")
    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setTitle(title, showProfile = false)
        val nightModeFlags = resources.configuration.uiMode and Configuration.UI_MODE_NIGHT_MASK
        if (nightModeFlags == Configuration.UI_MODE_NIGHT_YES) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.Q) {
                binding.webView.settings.forceDark = WebSettings.FORCE_DARK_ON
            }
        }
        binding.webView.webViewClient = MyWebViewClient(getLoadingView()?.progressBar)
        binding.webView.settings.javaScriptEnabled = true
        binding.webView.loadUrl(url)
        getLoadingView()?.progressBar?.showView()
    }

    private class MyWebViewClient constructor(
        private val progressBar: MKLoader?
    ) : WebViewClient() {

        override fun shouldOverrideUrlLoading(view: WebView?, url: String?): Boolean {
            return false
        }

        override fun onPageStarted(view: WebView?, url: String?, favicon: Bitmap?) {
            view?.goneView()
            progressBar?.showView()
            super.onPageStarted(view, url, favicon)
        }

        override fun onPageFinished(view: WebView?, url: String?) {
            view?.showView()
            progressBar?.goneView()
            super.onPageFinished(view, url)
        }
    }
}