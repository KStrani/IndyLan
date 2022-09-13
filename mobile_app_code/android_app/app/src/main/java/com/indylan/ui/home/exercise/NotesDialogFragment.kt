package com.indylan.ui.home.exercise

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import com.indylan.databinding.DialogNotesBinding
import com.indylan.ui.base.BaseDialogFragment

class NotesDialogFragment : BaseDialogFragment() {

    private val notes by lazy {
        NotesDialogFragmentArgs.fromBundle(requireArguments()).notes
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        val binding =
            DialogNotesBinding.inflate(LayoutInflater.from(context), container, false).apply {
                lifecycleOwner = viewLifecycleOwner
                notes = this@NotesDialogFragment.notes
                buttonOk.setOnClickListener {
                    dismissAllowingStateLoss()
                }
            }
        return binding.root
    }
}