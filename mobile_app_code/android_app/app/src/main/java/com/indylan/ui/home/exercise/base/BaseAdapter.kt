package com.indylan.ui.home.exercise.base

import android.graphics.drawable.Drawable
import android.graphics.drawable.TransitionDrawable
import android.view.View
import android.view.ViewGroup
import android.view.animation.AnimationUtils
import androidx.core.content.ContextCompat
import androidx.core.content.res.ResourcesCompat
import androidx.recyclerview.widget.RecyclerView
import com.indylan.R
import com.indylan.common.extensions.setAsButton

abstract class BaseAdapter<T, H : BaseViewHolder<T>>(
    private val recyclerView: RecyclerView,
    private val isSelectable: Boolean,
    private val callback: (Int, T) -> Unit
) : RecyclerView.Adapter<H>() {

    private var items = listOf<T>()
    private var selectedPosition: Int = -1

    abstract fun createHolder(parent: ViewGroup, callback: (Int, T) -> Unit): H

    abstract fun bind(item: T, holder: H)

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): H {
        val holder = createHolder(parent) { position, item ->
            if (isSelectable) {
                setSelectedPosition(position)
                deselectOthers()
            }
            callback.invoke(position, item)
        }
        holder.setIsRecyclable(false)
        return holder
    }

    override fun onBindViewHolder(holder: H, position: Int) {
        (holder as? BaseViewHolder<T>)?.bind(items[position])
        bind(items[position], holder)
    }

    fun setData(items: List<T>) {
        this.items = items
    }

    fun resetSelection() {
        selectedPosition = -1
        deselectOthers()
    }

    fun deselectOthers() {
        for (i in 0 until itemCount) {
            if (i == selectedPosition) {
                (recyclerView.findViewHolderForAdapterPosition(i) as? BaseViewHolder<*>)?.showSelected()
            } else {
                (recyclerView.findViewHolderForAdapterPosition(i) as? BaseViewHolder<*>)?.showDeselected()
            }
        }
    }

    fun getSelectedPosition(): Int {
        return selectedPosition
    }

    fun getSelectedItem(): T? {
        if (selectedPosition == -1 || selectedPosition >= itemCount) {
            return null
        }
        return items[selectedPosition]
    }

    fun setSelectedPosition(position: Int) {
        this.selectedPosition = position
    }

    fun showHint(position: Int, callback: (Unit) -> Unit = {}) {
        if (position != -1) {
            (recyclerView.findViewHolderForAdapterPosition(position) as? BaseViewHolder<*>)?.showHint(
                callback
            )
        }
    }

    fun showCorrectAnswer(position: Int, callback: (Unit) -> Unit = {}) {
        if (position != -1) {
            (recyclerView.findViewHolderForAdapterPosition(position) as? BaseViewHolder<*>)?.showCorrectAnswer(
                callback
            )
        }
    }

    fun showIncorrectAnswer(position: Int, callback: (Unit) -> Unit = {}) {
        if (position != -1) {
            (recyclerView.findViewHolderForAdapterPosition(position) as? BaseViewHolder<*>)?.showIncorrectAnswer(
                callback
            )
        }
    }

    fun disableAllButtons() {
        for (i in 0..itemCount) {
            (recyclerView.findViewHolderForAdapterPosition(i) as? BaseViewHolder<*>)?.disableButton()
        }
    }

    fun enableAllButtons() {
        for (i in 0..itemCount) {
            (recyclerView.findViewHolderForAdapterPosition(i) as? BaseViewHolder<*>)?.enableButton()
        }
    }

    override fun getItemCount(): Int = items.size

    fun getItems(): List<T> = items
}

abstract class BaseViewHolder<T>(
    view: View,
    private val callback: (Int, T) -> Unit
) : RecyclerView.ViewHolder(view) {

    private var animationDuration =
        view.context.resources.getInteger(R.integer.animation_duration)
    private val selectedBackground =
        ContextCompat.getDrawable(view.context, R.drawable.bg_green_selected)
    private val rightTransition = ResourcesCompat.getDrawable(
        view.resources,
        R.drawable.bg_right_answer_transition,
        null
    ) as TransitionDrawable?
    private val wrongTransition = ResourcesCompat.getDrawable(
        view.resources,
        R.drawable.bg_wrong_answer_transition,
        null
    ) as TransitionDrawable?
    private val animation = AnimationUtils.loadAnimation(view.context, R.anim.heartbeat)
    private var isAnimating = false
    private var isEnabled = true

    abstract fun getViewToAnimate(): View

    abstract fun background(): Drawable?

    fun bind(item: T) {
        getViewToAnimate().setOnClickListener {
            if (!isAnimating && isEnabled) {
                callback.invoke(adapterPosition, item)
            }
        }
        getViewToAnimate().setAsButton(isEnabled)
    }

    fun showSelected() {
        getViewToAnimate().background = selectedBackground
    }

    fun showDeselected() {
        getViewToAnimate().background = background()
    }

    fun showHint(callback: (Unit) -> Unit = {}) {
        if (!isAnimating) {
            getViewToAnimate().background = rightTransition
            getViewToAnimate().startAnimation(animation)
            rightTransition?.startTransition(animationDuration / 2)
            getViewToAnimate().postDelayed({
                rightTransition?.reverseTransition(animationDuration / 2)
            }, animationDuration / 2L)
            setNotAnimating(callback)
        }
    }

    fun showCorrectAnswer(callback: (Unit) -> Unit = {}) {
        if (!isAnimating) {
            getViewToAnimate().background = rightTransition
            rightTransition?.startTransition(animationDuration)
            setNotAnimating(callback)
        }
    }

    fun showIncorrectAnswer(callback: (Unit) -> Unit = {}) {
        if (!isAnimating) {
            getViewToAnimate().background = wrongTransition
            wrongTransition?.startTransition(animationDuration / 2)
            getViewToAnimate().postDelayed({
                wrongTransition?.reverseTransition(animationDuration / 2)
            }, animationDuration / 2L)
            setNotAnimating(callback)
        }
    }

    fun setNotAnimating(callback: (Unit) -> Unit = {}) {
        isAnimating = true
        getViewToAnimate().postDelayed({
            isAnimating = false
            callback.invoke(Unit)
        }, animationDuration * 1L)
    }

    fun disableButton() {
        isEnabled = false
        getViewToAnimate().setAsButton(isEnabled)
    }

    fun enableButton() {
        isEnabled = true
        getViewToAnimate().setAsButton(isEnabled)
    }
}