package com.indylan.ui.home.exercise

import android.graphics.drawable.TransitionDrawable
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.appcompat.widget.AppCompatTextView
import androidx.core.content.res.ResourcesCompat
import androidx.recyclerview.widget.RecyclerView
import com.indylan.R
import com.indylan.data.model.DialogList

class ChatItemSelectionAdapter(private val callback: (DialogList) -> Unit) :
    RecyclerView.Adapter<ChatItemSelectionAdapter.ChatItemSelectionViewHolder>() {

    private var dialogList: List<DialogList> = emptyList()

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ChatItemSelectionViewHolder {
        return ChatItemSelectionViewHolder(
            LayoutInflater.from(parent.context)
                .inflate(R.layout.item_chat_selection, parent, false),
            callback
        )
    }

    override fun getItemCount(): Int = dialogList.size

    override fun onBindViewHolder(holder: ChatItemSelectionViewHolder, position: Int) {
        holder.bindData(dialogList[position])
    }

    fun submitItems(items: List<DialogList>?) {
        dialogList = items ?: emptyList()
        notifyDataSetChanged()
    }

    inner class ChatItemSelectionViewHolder(
        view: View,
        private val callback: (DialogList) -> Unit
    ) : RecyclerView.ViewHolder(view) {

        private var animationDuration =
            view.context.resources.getInteger(R.integer.animation_duration)
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
        private val textView = view.findViewById<AppCompatTextView>(R.id.textView)
        private var isAnimating = false

        fun bindData(dialogList: DialogList) {
            textView.text = dialogList.fixPhrase()
            textView.tag = dialogList
            if (dialogList.isCorrect) {
                textView.background = rightTransition
            } else {
                textView.background = wrongTransition
            }
            textView.setOnClickListener {
                if (!isAnimating) {
                    (textView.background as TransitionDrawable).startTransition(animationDuration / 2)
                    it.postDelayed({
                        (textView.background as TransitionDrawable).reverseTransition(
                            animationDuration / 2
                        )
                        callback.invoke(dialogList)
                    }, animationDuration / 2L)
                }
            }
        }
    }
}