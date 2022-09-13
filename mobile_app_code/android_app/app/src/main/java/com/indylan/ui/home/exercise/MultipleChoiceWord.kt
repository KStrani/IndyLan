package com.indylan.ui.home.exercise

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.core.view.doOnLayout
import androidx.core.view.isVisible
import androidx.fragment.app.Fragment
import androidx.navigation.fragment.findNavController
import androidx.viewpager2.adapter.FragmentStateAdapter
import com.indylan.R
import com.indylan.data.model.ExerciseTranslation
import com.indylan.databinding.FragmentExerciseWordBinding
import com.indylan.ui.home.ExerciseFragment
import com.indylan.ui.home.ExerciseFragmentDirections
import com.indylan.ui.home.HomeActivity
import com.indylan.ui.home.exercise.base.BaseExerciseFragment

class MultipleChoiceWordExerciseFragment : BaseExerciseFragment() {

    lateinit var binding: FragmentExerciseWordBinding
    lateinit var exerciseTranslation: ExerciseTranslation
    var isAudio: Boolean = false
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
    ): View {
        binding = FragmentExerciseWordBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        binding.recyclerViewOptions.adapter = optionsAdapter
        optionsAdapter.setData(exerciseTranslation.option.orEmpty())
        optionsAdapter.correctAnswerPosition = optionsAdapter.findCorrectAnswerPosition()
        if (isAudio) {
            binding.audioViewMain.isVisible = true
            binding.textViewName.isVisible = false
            binding.audioView.isVisible = false
        } else {
            binding.audioViewMain.isVisible = false
            binding.textViewName.isVisible = true
            binding.textViewName.text = exerciseTranslation.word
            binding.audioView.isVisible =
                exerciseTranslation.isAudioAvailable == "1" && !exerciseTranslation.audio.isNullOrEmpty()
        }
        binding.audioView.setOnClickListener {
            binding.audioView.playAudio(exerciseTranslation.audio) {
                (activity as? HomeActivity)?.showMessage(getString(R.string.audio_not_found))
            }
        }
        binding.audioViewMain.setOnClickListener {
            binding.audioViewMain.playAudio(exerciseTranslation.audio) {
                (activity as? HomeActivity)?.showMessage(getString(R.string.audio_not_found))
            }
        }
        binding.imageViewInfo.isVisible = !exerciseTranslation.notes.isNullOrEmpty()
        binding.imageViewInfo.setOnClickListener {
            findNavController().navigate(
                ExerciseFragmentDirections.toNotesDialogFragment(
                    exerciseTranslation.notes ?: ""
                )
            )
        }
    }
}

class MultipleChoiceWordPagerAdapter(
    private val exerciseTranslations: List<ExerciseTranslation>,
    private val isAudio1: Boolean,
    fragment: Fragment
) : FragmentStateAdapter(fragment) {
    override fun getItemCount(): Int = exerciseTranslations.size

    override fun createFragment(position: Int): Fragment = MultipleChoiceWordExerciseFragment()
        .apply {
            this.exerciseTranslation = exerciseTranslations[position]
            this.isAudio = isAudio1
        }
}