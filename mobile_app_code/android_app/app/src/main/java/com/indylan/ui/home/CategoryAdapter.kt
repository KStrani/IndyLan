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
import com.indylan.data.model.Category
import com.indylan.databinding.ItemCategoryBinding

class CategoryAdapter(
    private val context: Context,
    private val lifecycleOwner: LifecycleOwner,
    private val callback: (Category) -> Unit
) : ListAdapter<Category, CategoryFlagViewHolder>(CategoryDiff) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): CategoryFlagViewHolder {
        val binding =
            ItemCategoryBinding.inflate(LayoutInflater.from(parent.context), parent, false)
                .apply {
                    root.layoutDirection = context.resources.configuration.layoutDirection
                    constraintLayoutCategory.setOnClickListener {
                        category?.let {
                            callback.invoke(it)
                        }
                    }
                }
        return CategoryFlagViewHolder(binding, lifecycleOwner)
    }

    override fun onBindViewHolder(holder: CategoryFlagViewHolder, position: Int) {
        holder.bind(getItem(position))
    }
}

class CategoryFlagViewHolder(
    private val binding: ItemCategoryBinding,
    private val lifecycleOwner: LifecycleOwner
) : RecyclerView.ViewHolder(binding.root) {

    fun bind(category: Category) {
        binding.category = category
        binding.lifecycleOwner = lifecycleOwner
        binding.executePendingBindings()
        if (category.image?.isValidImageFile() == true) {
            binding.cardCategory.showView()
            binding.progressBar.showView()
            GlideApp.with(binding.root.context)
                .load(category.image)
                .listener(object : RequestListener<Drawable> {
                    override fun onLoadFailed(
                        e: GlideException?,
                        model: Any?,
                        target: Target<Drawable>?,
                        isFirstResource: Boolean
                    ): Boolean {
                        binding.cardCategory.goneView()
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
                .into(binding.imageViewCategory)
        } else {
            binding.cardCategory.goneView()
        }
    }
}

object CategoryDiff : DiffUtil.ItemCallback<Category>() {
    override fun areItemsTheSame(oldItem: Category, newItem: Category): Boolean {
        return oldItem.id == newItem.id
    }

    override fun areContentsTheSame(oldItem: Category, newItem: Category): Boolean {
        return oldItem == newItem
    }
}