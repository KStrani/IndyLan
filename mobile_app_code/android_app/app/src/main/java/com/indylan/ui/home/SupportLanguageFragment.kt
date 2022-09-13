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
import com.indylan.databinding.FragmentSupportLanguageBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import com.indylan.widget.MarginItemDecoration
import com.yariksoffice.lingver.Lingver
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class SupportLanguageFragment : BaseFragment() {

    private val viewModel: HomeViewModel by viewModels()
    private lateinit var binding: FragmentSupportLanguageBinding
    private val adapter by lazy {
        SupportLanguageAdapter(viewLifecycleOwner) {
            Lingver.getInstance().setLocale(requireContext(), it.correctCountryCode() ?: "en")
            findNavController().navigate(
                SupportLanguageFragmentDirections.toTargetLanguageFragment(
                    it
                )
            )
        }
    }

    override fun getViewModel(): BaseViewModel = viewModel

    override fun onBackPress(): Boolean = true

    override fun getLoadingView(): LayoutProgressBinding = binding.includeProgress

    override fun getErrorView(): LayoutErrorBinding = binding.includeError

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentSupportLanguageBinding.inflate(inflater, container, false).apply {
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
        setTitle(getString(R.string.support_language), showBack = false)
        viewModel.supportLanguageLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewLanguages.showView()
            adapter.submitList(it)
        })
        viewModel.emptySupportLanguageLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewLanguages.goneView()
        })
        viewModel.fetchSupportLanguages()
        Lingver.getInstance().setLocale(requireContext(), "en")
    }
}