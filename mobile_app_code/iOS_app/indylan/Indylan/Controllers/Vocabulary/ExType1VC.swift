//
//  ExType1VC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import AVFoundation

class ExType1VC: ExerciseController, UITableViewDataSource, UITableViewDelegate, AVAudioPlayerDelegate {

    //MARK: DECLARATION
    
    @IBOutlet var scrView: BaseScrollView!
  
    @IBOutlet var contentView: UIView!
    
    @IBOutlet var imgViewQuestion: UIImageView!
    
    @IBOutlet var btnAudio: UIButton!
    
    @IBOutlet var lblQuestion: UILabel!
    
    @IBOutlet weak var labelHeightConstraint: NSLayoutConstraint!
    
    @IBOutlet var tblViewOptions: IntrinsicTableView!
    
    @IBOutlet var btnChooseOption: UIButton!
    
    @IBOutlet var aspectRatioImgView: NSLayoutConstraint!
    @IBOutlet var vwAudioNew : UIView!
    @IBOutlet var btnNewAudio : UIButton!
    
    var indicator : UIActivityIndicatorView!
    
    var audioPlayer: AVPlayer!
    
    var playerItem: AVPlayerItem!
    
    var arrType1Questions = Array<JSON>()
    
    var urlString = "" as String
    
    var questionIndex = 0
    
    var score = 0
    
    var attempts = 0
    
    var isFromNewSelection = false
    var btnName = ""
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        self.title = selectedCategory

        self.automaticallyAdjustsScrollViewInsets = false

        if (UserDefaults.standard.object(forKey: "type1ScrollAlert")) == nil {
            UserDefaults.standard.set( true, forKey: "type1ScrollAlert")
            UserDefaults.standard.synchronize()
            Alert.showWith("", message: "Scroll down to see all language options".localized(), completion: nil)
        }
        
        imgViewQuestion.layer.cornerRadius = 8
        
        tblViewOptions.register(UINib(nibName: "CellExType1", bundle: nil), forCellReuseIdentifier: "cellExType1")

        tblViewOptions.estimatedRowHeight = 50
        tblViewOptions.rowHeight = UITableViewAutomaticDimension
        
        btnChooseOption.setTitleColor(Colors.black, for: .normal)
        btnChooseOption.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 12)
        btnChooseOption.layer.borderWidth = 1
        btnChooseOption.layer.borderColor = Colors.border.cgColor
        btnChooseOption.layer.cornerRadius = 8
        btnChooseOption.setTitleColor(Colors.black, for: .normal)
        btnChooseOption.backgroundColor = Colors.white
        view.bringSubview(toFront: btnChooseOption)
        
        updateQuestion()
    }

    func updateQuestion()
    {
        if audioPlayer != nil
        {
            indicator.stopAnimating()

            audioPlayer.pause()
        }

        self.view.isUserInteractionEnabled = true
        
        attempts = 0
        if isFromNewSelection{
            btnAudio.isHidden = true
            UIView.transition(with: btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                self.btnChooseOption.setTitleColor(Colors.black, for: .normal)
                self.btnChooseOption.backgroundColor = Colors.white
                if self.btnName == "Sentences"{
                    self.btnChooseOption.setTitle("Sentences".localized(), for: .normal)
                }else{
                self.btnChooseOption.setTitle("words".localized(), for: .normal)
                }
            }, completion: nil)
        }else{
            btnAudio.isHidden = false
            vwAudioNew.isHidden = true
        UIView.transition(with: btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnChooseOption.setTitleColor(Colors.black, for: .normal)
            self.btnChooseOption.backgroundColor = Colors.white
            self.btnChooseOption.setTitle("chooseOption".localized(), for: .normal)
        }, completion: nil)
        }
        if arrType1Questions[questionIndex]["image_path"].exists()
        {
            imgViewQuestion.setImage(withURL: arrType1Questions[questionIndex]["image_path"].stringValue)
            imgViewQuestion.isHidden = false
            
            if labelHeightConstraint != nil {
                labelHeightConstraint.isActive = false
            }
        }
        else
        {
            imgViewQuestion.isHidden = true
        }
        
        if (arrType1Questions[questionIndex]["audio_file"].stringValue.isEmpty)
        {
            if (arrType1Questions[questionIndex]["audio_file"].stringValue.isEmpty){
                btnAudio.isHidden = true
                vwAudioNew.isHidden = true
            }
            else{
            if isFromNewSelection{
                btnAudio.isHidden = true
                vwAudioNew.isHidden = false
                self.vwAudioNew.layer.cornerRadius = self.vwAudioNew.frame.size.width / 2
                vwAudioNew.layer.shadowColor = UIColor.black.cgColor
                vwAudioNew.layer.shadowOpacity = 0.60
                vwAudioNew.layer.shadowOffset = .zero
                vwAudioNew.layer.shadowRadius = 5

            }else{
                vwAudioNew.isHidden = true
                btnAudio.isHidden = false
            }
        }
        }
        else
        {
            if isFromNewSelection{
                btnAudio.isHidden = true
            }else{
                vwAudioNew.isHidden = true
                btnAudio.isHidden = false
            }
            
            urlString = arrType1Questions[questionIndex]["audio_file"].stringValue
        }
        if isFromNewSelection{
            lblQuestion.isHidden = true
        }else{
            lblQuestion.isHidden = false
        lblQuestion.textColor = Colors.black
        lblQuestion.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
        lblQuestion.text = arrType1Questions[questionIndex]["word"].stringValue
        }
        tblViewOptions.reloadData()
        tblViewOptions.scrollToRow(at: IndexPath.init(row: 0, section: 0), at: UITableViewScrollPosition.top, animated: true)
    }
    
    // MARK: UITABLEVIEW DELEGATE METHODS
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        arrType1Questions[questionIndex]["option"].count
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        let cellOptions = tblViewOptions.dequeueReusableCell(withIdentifier: "cellExType1") as! CellExType1

        cellOptions.optionView.state = .normal
        
        cellOptions.lblOption.text = arrType1Questions[questionIndex]["option"][indexPath.row]["word"].stringValue

        return cellOptions
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        let cellOptions = tblViewOptions.cellForRow(at: indexPath) as! CellExType1
        
        attempts += 1
                
        if arrType1Questions[questionIndex]["option"][indexPath.row]["is_correct"].intValue == 1 {
            
            if attempts == 1 {
                score += 1
            }
            
            TapticEngine.notification.feedback(.success)
            
            self.view.isUserInteractionEnabled = false
            
            UIView.transition(with: btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                self.btnChooseOption.backgroundColor = Colors.green
                self.btnChooseOption.setTitleColor(Colors.white, for: .normal)
                self.btnChooseOption.setTitle("correct".localized(), for: .normal)
            }, completion: nil)
            
            UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                cellOptions.optionView.state = .green
            }, completion: { _ in
                    
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.3) {
                    self.questionIndex += 1
                        
                    self.view.isUserInteractionEnabled = true
                    
                    if (self.arrType1Questions.count > self.questionIndex)
                    {
                        UIView.animate(withDuration: 0.5, animations: {
                            let animation = CATransition()
                            animation.duration = 1.0
                            animation.startProgress = 0.0
                            animation.endProgress = 1.0
                            animation.timingFunction = CAMediaTimingFunction(name: kCAMediaTimingFunctionEaseOut)
                            animation.type = "pageCurl"
                            animation.subtype = "fromBottom"
                            animation.isRemovedOnCompletion = false
                            animation.fillMode = "extended"
                            self.view.layer.add(animation, forKey: "pageFlipAnimation")
                        })

                        self.updateQuestion()
                    }
                    else
                    {
                        let objExComplete = UIStoryboard.exercise.instantiateViewController(withClass: CongratsVC.self)!
                        objExComplete.score = self.score
                        objExComplete.totalQuestions = self.arrType1Questions.count
                        self.navigationController?.pushViewController(objExComplete, animated: true)
                    }
                }
            })
            
        }
        else
        {
            TapticEngine.notification.feedback(.error)
            
            cellOptions.shake()
            
            self.view.isUserInteractionEnabled = false
            
            UIView.transition(with: btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                self.btnChooseOption.backgroundColor = Colors.white
                self.btnChooseOption.setTitleColor(Colors.red, for: .normal)
                self.btnChooseOption.setTitle("retry".localized(), for: .normal)
            }, completion: { _ in
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                    
                    UIView.transition(with: self.btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                        self.btnChooseOption.backgroundColor = Colors.white
                        self.btnChooseOption.setTitleColor(Colors.black, for: .normal)
                        if self.isFromNewSelection{
                            if self.btnName == "Sentences"{
                                self.btnChooseOption.setTitle("Sentences".localized(), for: .normal)
                            }else{
                            self.btnChooseOption.setTitle("words".localized(), for: .normal)
                            }
//                            self.btnChooseOption.setTitle("Words".localized(), for: .normal)

                        }else{
                        self.btnChooseOption.setTitle("chooseOption".localized(), for: .normal)
                        }
                    }, completion: { _ in
                        self.view.isUserInteractionEnabled = true
                    })
                }
            })
            
            UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                    
                cellOptions.optionView.state = .red
                    
            },  completion: { (Bool) -> Void in
                
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                    UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                        cellOptions.optionView.state = .normal
                    })
                }
            })
        }
    }

// MARK: BUTTON ACTIONS
    @IBAction func btnNewAudioTap(){
        let url =  URL(string: urlString as String)
        
        if (url != nil)
        {
            if audioPlayer != nil
            {
                audioPlayer.removeObserver(self, forKeyPath: "status")
            }

            playerItem = AVPlayerItem(url: url!)

            indicator = UIActivityIndicatorView(activityIndicatorStyle: .white)
            indicator.center = btnAudio.center
            view.addSubview(indicator)

            DispatchQueue.main.asyncAfter(deadline: .now() + 0.0) {
                self.btnAudio.isUserInteractionEnabled = false
                self.indicator.startAnimating()
            }
        
            audioPlayer = AVPlayer(playerItem:playerItem)
            audioPlayer.addObserver( self, forKeyPath:"status", options:.initial, context:nil)
            
            audioPlayer.play()
        }
        else
        {
            SnackBar.show("Audio player error")
        }
        
    }
    @IBAction func btnAudioClicked(_ sender: Any)
    {
        let url =  URL(string: urlString as String)
        
        if (url != nil)
        {
            if audioPlayer != nil
            {
                audioPlayer.removeObserver(self, forKeyPath: "status")
            }

            playerItem = AVPlayerItem(url: url!)

            indicator = UIActivityIndicatorView(activityIndicatorStyle: .white)
            indicator.center = btnAudio.center
            view.addSubview(indicator)

            DispatchQueue.main.asyncAfter(deadline: .now() + 0.0) {
                self.btnAudio.isUserInteractionEnabled = false
                self.indicator.startAnimating()
            }
        
            audioPlayer = AVPlayer(playerItem:playerItem)
            audioPlayer.addObserver( self, forKeyPath:"status", options:.initial, context:nil)
            
            audioPlayer.play()
        }
        else
        {
            SnackBar.show("Audio player error")
        }
        
    }
    
    override func observeValue(forKeyPath keyPath: String?, of object: Any?, change: [NSKeyValueChangeKey : Any]?, context: UnsafeMutableRawPointer?) {
        if keyPath == "status" {
            indicator.stopAnimating()
            btnAudio.isUserInteractionEnabled = true
        }
    }
    
    @IBAction func btnOptionClicked(_ sender: Any) {
        
    }

    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        
        if audioPlayer != nil {
            audioPlayer.removeObserver(self, forKeyPath: "status")
        }
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
