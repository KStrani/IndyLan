package com.indylan.ui.home.exercise

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.core.view.doOnLayout
import androidx.fragment.app.Fragment
import androidx.viewpager2.adapter.FragmentStateAdapter
import com.indylan.common.glide.GlideApp
import com.indylan.data.model.ExerciseTranslation
import com.indylan.databinding.FragmentExerciseTranslationBinding
import com.indylan.ui.home.ExerciseFragment
import com.indylan.ui.home.exercise.base.BaseExerciseFragment

class MultipleChoiceImageExerciseFragment : BaseExerciseFragment() {

    lateinit var binding: FragmentExerciseTranslationBinding
    lateinit var exerciseTranslation: ExerciseTranslation
    private var answeredWrong = false
    private var isAnimating = false
    private val optionsAdapter: OptionsAdapter by lazy {
        OptionsAdapter(binding.recyclerViewOptions) { position, _ ->
            if (!isAnimating) {
                isAnimating = true
                if (position == optionsAdapter.correctAnswerPosition) {
                    optionsAdapter.showCorrectAnswer(position) {
                        isAnimating = false
                        if (!answeredWrong) {
                            (parentFragment as? ExerciseFragment)?.increaseScore()
                        }
                        (parentFragment as? ExerciseFragment)?.nextQuestion()
                    }
                } else {
                    optionsAdapter.showIncorrectAnswer(position) {
                        isAnimating = false
                    }
                    answeredWrong = true
                }
            }
        }
    }

    override fun onFocusGained() {
        view?.doOnLayout {
            (parentFragment as ExerciseFragment).showExerciseTypeName()
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        binding = FragmentExerciseTranslationBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        binding.recyclerViewOptions.adapter = optionsAdapter
        optionsAdapter.setData(exerciseTranslation.option.orEmpty())
        optionsAdapter.correctAnswerPosition = optionsAdapter.findCorrectAnswerPosition()
        GlideApp.with(this).load(exerciseTranslation.image).into(binding.imageView)
        binding.textViewName.text = exerciseTranslation.word
    }
}

class MultipleChoiceImagePagerAdapter(
    private val exerciseTranslations: List<ExerciseTranslation>,
    fragment: Fragment
) : FragmentStateAdapter(fragment) {
    override fun getItemCount(): Int = exerciseTranslations.size

    override fun createFragment(position: Int): Fragment = MultipleChoiceImageExerciseFragment()
        .apply {
            this.exerciseTranslation = exerciseTranslations[position]
        }
}