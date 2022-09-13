package com.indylan.ui.home.exercise

import android.graphics.drawable.TransitionDrawable
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.core.content.res.ResourcesCompat
import androidx.core.view.doOnLayout
import androidx.core.view.isVisible
import androidx.fragment.app.Fragment
import androidx.viewpager2.adapter.FragmentStateAdapter
import com.indylan.R
import com.indylan.common.glide.GlideApp
import com.indylan.data.model.ExercisePictureAnswer
import com.indylan.databinding.FragmentExerciseChooseImageBinding
import com.indylan.ui.home.ExerciseFragment
import com.indylan.ui.home.HomeActivity
import com.indylan.ui.home.exercise.base.BaseExerciseFragment

class PictureAnswerExerciseFragment : BaseExerciseFragment() {

    lateinit var binding: FragmentExerciseChooseImageBinding
    lateinit var exercisePictureAnswer: ExercisePictureAnswer
    private var answeredWrong = false
    private var isAnimating = false

    private val rightTransition: TransitionDrawable by lazy {
        ResourcesCompat.getDrawable(
            resources,
            R.drawable.bg_right_answer_transition_square,
            null
        ) as TransitionDrawable
    }
    private val wrongTransition: TransitionDrawable by lazy {
        ResourcesCompat.getDrawable(
            resources,
            R.drawable.bg_wrong_answer_transition_square,
            null
        ) as TransitionDrawable
    }
    private val animationDuration by lazy {
        resources.getInteger(R.integer.animation_duration) * 1L
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
        binding = FragmentExerciseChooseImageBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            audioView.isVisible =
                exercisePictureAnswer.isAudioAvailable == "1" && !exercisePictureAnswer.audio.isNullOrEmpty()
            audioView.setOnClickListener {
                binding.audioView.playAudio(exercisePictureAnswer.audio) {
                    (activity as? HomeActivity)?.showMessage(getString(R.string.audio_not_found))
                }
            }
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        binding.textViewName.text = exercisePictureAnswer.word

        // Load data and listeners for first image
        GlideApp.with(this).load(exercisePictureAnswer.option?.get(0)?.image)
            .into(binding.imageView)
        binding.viewOverlay1.background = if (exercisePictureAnswer.option?.get(0)?.isCorrect == 1)
            rightTransition
        else
            wrongTransition
        binding.imageView.setOnClickListener {
            showTransition(binding.viewOverlay1) {
                if (exercisePictureAnswer.option?.get(0)?.isCorrect == 1) {
                    if (!answeredWrong) {
                        (parentFragment as? ExerciseFragment)?.increaseScore()
                    }
                    (parentFragment as? ExerciseFragment)?.nextQuestion()
                } else {
                    answeredWrong = true
                }
            }
        }

        // Load data and listeners for second image
        GlideApp.with(this).load(exercisePictureAnswer.option?.get(1)?.image)
            .into(binding.imageView2)
        binding.viewOverlay2.background = if (exercisePictureAnswer.option?.get(1)?.isCorrect == 1)
            rightTransition
        else
            wrongTransition
        binding.imageView2.setOnClickListener {
            showTransition(binding.viewOverlay2) {
                if (exercisePictureAnswer.option?.get(1)?.isCorrect == 1) {
                    if (!answeredWrong) {
                        (parentFragment as? ExerciseFragment)?.increaseScore()
                    }
                    (parentFragment as? ExerciseFragment)?.nextQuestion()
                } else {
                    answeredWrong = true
                }
            }
        }
    }

    private fun showTransition(view: View, callback: (Unit) -> Unit) {
        if (!isAnimating) {
            isAnimating = true
            (view.background as? TransitionDrawable)?.startTransition(animationDuration.toInt() / 2)
            view.postDelayed({
                (view.background as? TransitionDrawable)?.reverseTransition(animationDuration.toInt() / 2)
            }, animationDuration / 2L)
            view.postDelayed({
                callback.invoke(Unit)
                isAnimating = false
            }, animationDuration)
        }
    }
}

class PictureAnswerPagerAdapter(
    private val exercisePictureAnswers: List<ExercisePictureAnswer>,
    fragment: Fragment
) : FragmentStateAdapter(fragment) {
    override fun getItemCount(): Int = exercisePictureAnswers.size

    override fun createFragment(position: Int): Fragment = PictureAnswerExerciseFragment()
        .apply {
            this.exercisePictureAnswer = exercisePictureAnswers[position]
        }
}