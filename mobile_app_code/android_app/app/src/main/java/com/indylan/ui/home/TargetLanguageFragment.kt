package com.indylan.ui.home

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.viewModels
import androidx.navigation.fragment.findNavController
import com.indylan.R
import com.indylan.common.extensions.goneView
import com.indylan.common.extensions.showView
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.FragmentTargetLanguageBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import com.indylan.widget.MarginItemDecoration
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class TargetLanguageFragment : BaseFragment() {

    private val viewModel: HomeViewModel by viewModels()
    private lateinit var binding: FragmentTargetLanguageBinding
    private val adapter by lazy {
        LanguageFlagAdapter(requireContext(), viewLifecycleOwner) {
            findNavController().navigate(
                TargetLanguageFragmentDirections.toExerciseModeFragment(
                    supportLanguage,
                    it,
                    it
                )
            )
        }
    }
    private val supportLanguage by lazy {
        TargetLanguageFragmentArgs.fromBundle(requireArguments()).supportLanguage
    }
    /*private val menuLanguage by lazy {
        TargetLanguageFragmentArgs.fromBundle(requireArguments()).menuLanguage
    }*/

    override fun getViewModel(): BaseViewModel = viewModel

    override fun onBackPress(): Boolean = true

    override fun getLoadingView(): LayoutProgressBinding = binding.includeProgress

    override fun getErrorView(): LayoutErrorBinding = binding.includeError

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentTargetLanguageBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            recyclerViewLanguages.adapter = adapter
            recyclerViewLanguages.addItemDecoration(
                MarginItemDecoration(
                    resources.getDimension(R.dimen.margin_recyclerview).toInt()
                )
            )
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setTitle(getString(R.string.target_language))
        viewModel.targetLanguageLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewLanguages.showView()
            adapter.submitList(it)
        })
        viewModel.emptyTargetLanguageLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewLanguages.goneView()
        })
        viewModel.fetchTargetLanguages()
    }
}