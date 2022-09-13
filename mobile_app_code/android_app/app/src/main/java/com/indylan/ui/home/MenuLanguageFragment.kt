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
import com.indylan.databinding.FragmentMenuLanguageBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import com.indylan.widget.MarginItemDecoration
import com.yariksoffice.lingver.Lingver
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class MenuLanguageFragment : BaseFragment() {

    private val viewModel: HomeViewModel by viewModels()
    private lateinit var binding: FragmentMenuLanguageBinding
    private val adapter by lazy {
        LanguageFlagAdapter(requireContext(), viewLifecycleOwner) {
            Lingver.getInstance().setLocale(requireContext(), it.correctCountryCode() ?: "en")
            findNavController().navigate(
                MenuLanguageFragmentDirections.toTargetLanguageFragment(
                    supportLanguage//,
                    //it
                )
            )
        }
    }
    private val supportLanguage by lazy {
        MenuLanguageFragmentArgs.fromBundle(requireArguments()).supportLanguage
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
        binding = FragmentMenuLanguageBinding.inflate(inflater, container, false).apply {
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
        setTitle(getString(R.string.menu_language))
        viewModel.menuLanguageLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewLanguages.showView()
            adapter.submitList(it)
        })
        viewModel.emptyMenuLanguageLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewLanguages.goneView()
        })
        viewModel.fetchMenuLanguages()
        Lingver.getInstance().setLocale(requireContext(), "en")
    }
}