package com.indylan.ui.home

import android.content.Context
import android.graphics.drawable.Drawable
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.lifecycle.LifecycleOwner
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.load.DataSource
import com.bumptech.glide.load.engine.GlideException
import com.bumptech.glide.request.RequestListener
import com.bumptech.glide.request.target.Target
import com.indylan.common.extensions.goneView
import com.indylan.common.extensions.isValidImageFile
import com.indylan.common.extensions.showView
import com.indylan.common.glide.GlideApp
import com.indylan.data.model.ExerciseType
import com.indylan.databinding.ItemExerciseTypeBinding

class ExerciseTypeAdapter(
    private val context: Context,
    private val lifecycleOwner: LifecycleOwner,
    private val callback: (ExerciseType) -> Unit
) : ListAdapter<ExerciseType, ExerciseTypeFlagViewHolder>(ExerciseTypeDiff) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ExerciseTypeFlagViewHolder {
        val binding =
            ItemExerciseTypeBinding.inflate(LayoutInflater.from(parent.context), parent, false)
                .apply {
                    root.layoutDirection = context.resources.configuration.layoutDirection
                    constraintLayoutExerciseType.setOnClickListener {
                        exerciseType?.let {
                            callback.invoke(it)
                        }
                    }
                }
        return ExerciseTypeFlagViewHolder(binding, lifecycleOwner)
    }

    override fun onBindViewHolder(holder: ExerciseTypeFlagViewHolder, position: Int) {
        holder.bind(getItem(position))
    }
}

class ExerciseTypeFlagViewHolder(
    private val binding: ItemExerciseTypeBinding,
    private val lifecycleOwner: LifecycleOwner
) : RecyclerView.ViewHolder(binding.root) {

    fun bind(exerciseType: ExerciseType) {
        binding.exerciseType = exerciseType
        binding.lifecycleOwner = lifecycleOwner
        binding.executePendingBindings()
        if (exerciseType.image?.isValidImageFile() == true) {
            binding.cardExerciseType.showView()
            binding.progressBar.showView()
            GlideApp.with(binding.root.context)
                .load(exerciseType.image)
                .listener(object : RequestListener<Drawable> {
                    override fun onLoadFailed(
                        e: GlideException?,
                        model: Any?,
                        target: Target<Drawable>?,
                        isFirstResource: Boolean
                    ): Boolean {
                        binding.cardExerciseType.goneView()
                        return false
                    }

                    override fun onResourceReady(
                        resource: Drawable?,
                        model: Any?,
                        target: Target<Drawable>?,
                        dataSource: DataSource?,
                        isFirstResource: Boolean
                    ): Boolean {
                        binding.progressBar.goneView()
                        return false
                    }

                })
                .into(binding.imageViewExerciseType)
        } else {
            binding.cardExerciseType.goneView()
        }
    }
}

object ExerciseTypeDiff : DiffUtil.ItemCallback<ExerciseType>() {
    override fun areItemsTheSame(oldItem: ExerciseType, newItem: ExerciseType): Boolean {
        return oldItem.id == newItem.id
    }

    override fun areContentsTheSame(oldItem: ExerciseType, newItem: ExerciseType): Boolean {
        return oldItem == newItem
    }
}