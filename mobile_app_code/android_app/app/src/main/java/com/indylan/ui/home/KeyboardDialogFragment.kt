package com.indylan.ui.home

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import com.indylan.databinding.DialogKeyboardBinding
import com.indylan.ui.base.BaseDialogFragment

class KeyboardDialogFragment : BaseDialogFragment() {

    private val targetLanguage by lazy {
        KeyboardDialogFragmentArgs.fromBundle(requireArguments()).targetLanguage
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        val binding =
            DialogKeyboardBinding.inflate(LayoutInflater.from(context), container, false).apply {
                lifecycleOwner = viewLifecycleOwner
                buttonOk.setOnClickListener {
                    dismissAllowingStateLoss()
                }
                textViewKeyboardMessage.text =
                    "Please select ${targetLanguage.name} keyboard from settings. Go to Settings -> System -> Languages & input -> Virtual Keyboard -> Keyboard -> Languages -> Add Keyboard and select ${targetLanguage.name}"
            }
        return binding.root
    }
}