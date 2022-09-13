package com.indylan.ui.home.exercise

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import androidx.viewpager2.adapter.FragmentStateAdapter
import com.indylan.databinding.FragmentExerciseOtherBinding
import com.indylan.ui.home.exercise.base.BaseExerciseFragment

class OtherExerciseFragment : BaseExerciseFragment() {

    lateinit var binding: FragmentExerciseOtherBinding

    override fun onFocusGained() {

    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        binding = FragmentExerciseOtherBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
        }
        return binding.root
    }
}

class OtherPagerAdapter(
    fragment: Fragment
) : FragmentStateAdapter(fragment) {
    override fun getItemCount(): Int = 1

    override fun createFragment(position: Int): Fragment = OtherExerciseFragment()
}