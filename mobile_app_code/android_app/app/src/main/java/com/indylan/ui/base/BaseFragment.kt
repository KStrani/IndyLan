package com.indylan.ui.base

import android.content.Intent
import android.os.Bundle
import android.view.View
import androidx.activity.addCallback
import androidx.appcompat.app.AlertDialog
import androidx.fragment.app.Fragment
import androidx.navigation.fragment.findNavController
import com.google.android.material.dialog.MaterialAlertDialogBuilder
import com.indylan.R
import com.indylan.common.extensions.goneView
import com.indylan.common.extensions.hideKeyBoard
import com.indylan.common.extensions.showView
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding

abstract class BaseFragment : Fragment() {

    companion object {
        const val REQUEST_CODE_MESSAGE = 1234
    }

    private var progress: AlertDialog? = null

    abstract fun getViewModel(): BaseViewModel

    abstract fun onBackPress(): Boolean

    abstract fun getLoadingView(): LayoutProgressBinding?

    abstract fun getErrorView(): LayoutErrorBinding?

    /*override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enterTransition = MaterialSharedAxis.create(MaterialSharedAxis.Y, true)
        exitTransition = MaterialSharedAxis.create(MaterialSharedAxis.Y, false)
    }*/

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        hideKeyBoard()
        (activity as? BaseActivity)?.onBackPressedDispatcher?.addCallback(viewLifecycleOwner) {
            if (onBackPress()) {
                if (!findNavController().navigateUp()) {
                    activity?.finish()
                }
            }
        }
        getViewModel().messageLiveData.observe(viewLifecycleOwner, EventObserver {
            it?.let {
                getErrorView()?.linearLayoutError?.showView()
                getErrorView()?.textViewError?.text = it
            }
        })
        getViewModel().snackBarLiveData.observe(viewLifecycleOwner, EventObserver {
            it?.let {
                showMessage(it)
            }
        })
        getViewModel().showLoadingDialogLiveData.observe(viewLifecycleOwner, EventObserver {
            showLoadingDialog()
        })
        getViewModel().hideLoadingDialogLiveData.observe(viewLifecycleOwner, EventObserver {
            dismissLoadingDialog()
        })
        getViewModel().showLoadingLiveData.observe(viewLifecycleOwner, EventObserver {
            getErrorView()?.linearLayoutError?.goneView()
            getLoadingView()?.progressBar?.showView()
        })
        getViewModel().hideLoadingLiveData.observe(viewLifecycleOwner, EventObserver {
            getErrorView()?.linearLayoutError?.goneView()
            getLoadingView()?.progressBar?.goneView()
        })
        getViewModel().backLiveData.observe(viewLifecycleOwner, EventObserver {
            findNavController().popBackStack()
        })
    }

    fun setTitle(
        title: String,
        subtitle: String? = null,
        showBack: Boolean = true,
        showProfile: Boolean = true,
        showLogout: Boolean = false,
        profileCallback: (Unit) -> (Unit) = {
            findNavController().navigate(R.id.profileFragment)
        },
        logoutCallback: (Unit) -> (Unit) = {
            MaterialAlertDialogBuilder(requireContext())
                .setTitle("Logout?")
                .setMessage("Are you sure you want to logout?")
                .setPositiveButton("Logout") { _, _ ->
                    unauthorize()
                }
                .setNegativeButton("Cancel", null)
                .show()
        }
    ) {
        (activity as? BaseActivity)?.setTitle(
            title,
            subtitle,
            showBack,
            showProfile,
            showLogout,
            profileCallback,
            logoutCallback
        )
    }

    fun showMessage(message: String?) {
        (activity as? BaseActivity)?.showMessage(message)
    }

    fun showLoadingDialog() {
        if (progress == null) {
            val builder = MaterialAlertDialogBuilder(requireContext())
            builder.setView(R.layout.layout_progress_dialog)
            progress = builder.create()
            progress?.setCancelable(false)
            progress?.setCanceledOnTouchOutside(false)
        }
        progress?.show()
        progress?.window?.setBackgroundDrawable(null)
    }

    fun dismissLoadingDialog() {
        if (progress?.isShowing == true) {
            progress?.dismiss()
        }
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        if (requestCode == REQUEST_CODE_MESSAGE) {
            val message = data?.getStringExtra("message")
            showMessage(message)
        } else {
            super.onActivityResult(requestCode, resultCode, data)
        }
    }

    fun unauthorize() {
        getViewModel().logout()
        (activity as? BaseActivity)?.unauthorize()
    }

    fun authorize() {
        (activity as? BaseActivity)?.authorize()
    }
}