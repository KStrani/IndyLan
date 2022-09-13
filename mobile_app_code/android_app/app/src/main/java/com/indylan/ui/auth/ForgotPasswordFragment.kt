package com.indylan.ui.auth

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.view.inputmethod.EditorInfo
import androidx.fragment.app.viewModels
import com.indylan.R
import com.indylan.common.extensions.hideKeyBoard
import com.indylan.common.extensions.isValidEmail
import com.indylan.databinding.FragmentForgotPasswordBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class ForgotPasswordFragment : BaseFragment() {

    private val viewModel: AuthenticationViewModel by viewModels()
    private lateinit var binding: FragmentForgotPasswordBinding

    override fun getViewModel(): BaseViewModel = viewModel

    override fun onBackPress(): Boolean = true

    override fun getLoadingView(): LayoutProgressBinding? = null

    override fun getErrorView(): LayoutErrorBinding? = null

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        binding = FragmentForgotPasswordBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setTitle(getString(R.string.forgot_password), showProfile = false)
        binding.textInputEditTextEmail.setOnEditorActionListener { _, actionId, _ ->
            if (actionId == EditorInfo.IME_ACTION_DONE) {
                hideKeyBoard()
                binding.buttonSendMyPassword.callOnClick()
                return@setOnEditorActionListener true
            }
            return@setOnEditorActionListener false
        }
        binding.buttonSendMyPassword.setOnClickListener {
            hideKeyBoard()
            val email = binding.textInputEditTextEmail.text.toString()

            if (email.isEmpty()) {
                showMessage(getString(R.string.validation_empty_email))
                return@setOnClickListener
            }

            if (!email.isValidEmail()) {
                showMessage(getString(R.string.validation_invalid_email))
                return@setOnClickListener
            }

            viewModel.forgotPassword(email)
        }
    }
}