package com.indylan.ui.home.exercise

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.core.view.doOnLayout
import androidx.core.view.isVisible
import androidx.fragment.app.Fragment
import androidx.viewpager2.adapter.FragmentStateAdapter
import com.indylan.R
import com.indylan.common.extensions.goneView
import com.indylan.common.glide.GlideApp
import com.indylan.data.model.ExerciseSingleAnswer
import com.indylan.data.model.ExerciseType
import com.indylan.data.model.ExerciseTypeEnum
import com.indylan.databinding.FragmentExerciseFlashCardBinding
import com.indylan.ui.home.ExerciseFragment
import com.indylan.ui.home.HomeActivity
import com.indylan.ui.home.exercise.base.BaseExerciseFragment

class FlashCardExerciseFragment : BaseExerciseFragment() {

    lateinit var binding: FragmentExerciseFlashCardBinding
    lateinit var exerciseSingleAnswer: ExerciseSingleAnswer
    lateinit var exerciseType: ExerciseType

    override fun onFocusGained() {
        view?.doOnLayout {
            (parentFragment as ExerciseFragment).showButton()
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentExerciseFlashCardBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            textViewTranslation.setOnClickListener {
                textViewTranslation.text = exerciseSingleAnswer.option
            }
            if (exerciseType.parseExerciseType() == ExerciseTypeEnum.FLASH_CARDS_IMAGE) {
                GlideApp.with(this@FlashCardExerciseFragment).load(exerciseSingleAnswer.image)
                    .into(imageView)
            } else {
                imageView.goneView()
            }
            textViewName.text = exerciseSingleAnswer.word
            audioView.isVisible =
                exerciseSingleAnswer.isAudioAvailable == "1" && !exerciseSingleAnswer.audio.isNullOrEmpty()
            audioView.setOnClickListener {
                audioView.playAudio(exerciseSingleAnswer.audio) {
                    (activity as? HomeActivity)?.showMessage(getString(R.string.audio_not_found))
                }
            }
        }
        return binding.root
    }
}

class FlashCardPagerAdapter(
    private val exerciseType1: ExerciseType,
    private val exerciseSingleAnswers: List<ExerciseSingleAnswer>,
    fragment: Fragment
) : FragmentStateAdapter(fragment) {
    override fun getItemCount(): Int = exerciseSingleAnswers.size

    override fun createFragment(position: Int): Fragment = FlashCardExerciseFragment()
        .apply {
            this.exerciseSingleAnswer = exerciseSingleAnswers[position]
            this.exerciseType = exerciseType1
        }
}