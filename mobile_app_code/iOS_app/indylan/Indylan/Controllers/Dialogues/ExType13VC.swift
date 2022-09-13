//
//  ExType13VC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import AVFoundation

class ExType13VC: ExerciseController, UITableViewDelegate, UITableViewDataSource {
    
    //MARK: DECLARATION
    
    @IBOutlet var scrView: BaseScrollView!
    
    @IBOutlet var lblTitle: UILabel!
    
    @IBOutlet var tblViewDialogue: IntrinsicTableView!
    
    @IBOutlet weak var btnContinue: UIButton!
    @IBOutlet weak var btnChooseOption: UIButton!
    
    @IBOutlet weak var svButtons: UIStackView!
    
    @IBOutlet var btnAudio: UIButton!

    var indicator: UIActivityIndicatorView!
    
    var audioPlayer: AVPlayer!
    
    var playerItem: AVPlayerItem!
    
    var urlString = "" as String
    
    var arrType13Questions = Array<JSON>()
    
    var tempOptions = Array<JSON>()
    
    var questionIndex = 0
    
    var score = 0
    
    var finalScore = 0
    
    var attempts = 0
    
    var lastIndex = 0
    
    var choiceCount = 1
    
    override func viewDidLoad() {
        
        super.viewDidLoad()
        
        self.automaticallyAdjustsScrollViewInsets = false
        
        self.title = selectedCategory

        lblTitle.textColor = Colors.black
        lblTitle.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
        
        tblViewDialogue.register(UINib(nibName: "CellSpeaker1", bundle: nil), forCellReuseIdentifier: "CellSpeaker1")
        tblViewDialogue.register(UINib(nibName: "CellSpeaker2", bundle: nil), forCellReuseIdentifier: "CellSpeaker2")
        tblViewDialogue.register(UINib(nibName: "CellExType1", bundle: nil), forCellReuseIdentifier: "cellExType1")
        
        tblViewDialogue.estimatedRowHeight = 70
        tblViewDialogue.rowHeight = UITableViewAutomaticDimension
        
        btnChooseOption.isUserInteractionEnabled = false
        btnChooseOption.layer.borderColor = Colors.border.cgColor
        btnChooseOption.layer.borderWidth = 1
        btnChooseOption.layer.cornerRadius = 8
        btnChooseOption.setTitleColor(Colors.black, for: .normal)
        btnChooseOption.backgroundColor = Colors.white
        btnChooseOption.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 12)
        
        btnContinue.isUserInteractionEnabled = true
        btnContinue.layer.borderColor = Colors.border.cgColor
        btnContinue.layer.borderWidth = 1
        btnContinue.layer.cornerRadius = 8
        btnContinue.setTitleColor(Colors.black, for: .normal)
        btnContinue.backgroundColor = Colors.white
        btnContinue.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 12)
        
//        svButtons.isLayoutMarginsRelativeArrangement = true
        view.bringSubview(toFront: svButtons)
        
        self.updateQuestion()
    }

    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        
        if audioPlayer != nil {
            audioPlayer.removeObserver(self, forKeyPath: "status")
            NotificationCenter.default.removeObserver(self, name: NSNotification.Name.AVPlayerItemDidPlayToEndTime, object: self.view.window)
        }
    }
    
    //MARK: FUNCTIONS
    
    func updateQuestion() {
        
        scrView.contentOffset.y = 0
        
        if audioPlayer != nil {
            indicator.stopAnimating()
            audioPlayer.pause()
        }
        
        if (arrType13Questions[questionIndex]["full_audio"].stringValue.isEmpty)
        {
            btnAudio.isHidden = true
        }
        else
        {
            btnAudio.isHidden = false
            urlString = arrType13Questions[questionIndex]["full_audio"].stringValue
        }
        
        self.view.isUserInteractionEnabled = true
        
        if selectedTargetLanguageId == "38" {
            lblTitle.text = arrType13Questions[questionIndex]["title"].stringValue.replacingOccurrences(of: "$", with: ".")
        } else {
            lblTitle.text = arrType13Questions[questionIndex]["title"].stringValue
        }
        
        attempts = 0
        score = 0
        
        if Int(selectedExTypeId) == 14 {
            btnContinue.alpha = 0
            btnContinue.isHidden = true
            
            btnChooseOption.alpha = 1
            btnChooseOption.isHidden = false
            
            btnChooseOption.setTitle("chooseOption".localized(), for: .normal)
            btnChooseOption.setTitleColor(Colors.black, for: .normal)
            btnChooseOption.backgroundColor = Colors.white
            
//            svButtons.layoutMargins = UIEdgeInsets.zero
        } else {
            btnContinue.alpha = 1
            btnContinue.isHidden = false
            
            btnChooseOption.alpha = 0
            btnChooseOption.isHidden = true
            
//            svButtons.layoutMargins = UIEdgeInsets(top: 15, left: 0, bottom: 20, right: 0)
        }

        if Int(selectedExTypeId) == 14
        {
            if (UserDefaults.standard.object(forKey: "type14ScrollAlert")) == nil
            {
                UserDefaults.standard.set(true, forKey: "type14ScrollAlert")
                UserDefaults.standard.synchronize()
                
                Alert.showWith("", message: "Scroll down to see all language options".localized(), completion: nil)
            }
            
            choiceCount = 1
            
            lastIndex = 2
            
            createOptionsArray()
        }
        
        tblViewDialogue.reloadData()
    }
    
    func createOptionsArray()
    {
        tempOptions.removeAll()
        
        var arrOptions = Array(arrType13Questions[questionIndex]["list"].arrayValue.dropFirst(lastIndex))
        
        if arrOptions.count > 1 {
            var newTempOptions = [JSON]()
            newTempOptions.append(arrOptions[0])
            
            arrOptions.remove(at: 0)
            
            for _ in 0..<(arrOptions.count > 3 ? 3: arrOptions.count) {
                let index = Int(arc4random_uniform(UInt32(arrOptions.count - 1)))
                newTempOptions.append(arrOptions[index])
                arrOptions.remove(at: index)
            }
            
            tempOptions = newTempOptions.shuffled()
        }
        else {
            lastIndex += 1
        }
    }
    
    @objc func cellBtnAudioPressed(sender: UIButton) {

        let audioUrl = "\(arrType13Questions[questionIndex]["list"][sender.tag]["audio_name"].stringValue)"
        
        let url =  URL(string: audioUrl as String)
        
        if (url != nil)
        {
            if audioPlayer != nil
            {
                indicator.stopAnimating()
                audioPlayer.pause()
                audioPlayer.removeObserver(self, forKeyPath: "status")
                NotificationCenter.default.removeObserver(self, name: NSNotification.Name.AVPlayerItemDidPlayToEndTime, object: self.view.window)
            }
            
            playerItem = AVPlayerItem(url: url!)
            
            indicator = UIActivityIndicatorView(activityIndicatorStyle: .white)
            indicator.center = sender.center
            sender.superview?.addSubview(indicator)
            
            DispatchQueue.main.asyncAfter(deadline: .now() + 0.0) {
                self.indicator.startAnimating()
            }
            
            audioPlayer = AVPlayer(playerItem:playerItem)
            
            audioPlayer.addObserver( self, forKeyPath:"status", options:.initial, context:nil)
            
            NotificationCenter.default.addObserver(self,selector:#selector(ExType13VC.playerDidFinishPlaying), name: NSNotification.Name.AVPlayerItemDidPlayToEndTime, object: playerItem)
            
            audioPlayer.play()
        }
        else
        {
            SnackBar.show("Audio error")
        }
    }
    
    @objc func playerDidFinishPlaying()
    {
        
    }
    
    override func observeValue(forKeyPath keyPath: String?, of object: Any?, change: [NSKeyValueChangeKey : Any]?, context: UnsafeMutableRawPointer?) {
        
        if keyPath == "status" {
            indicator.stopAnimating()
            btnAudio.isUserInteractionEnabled = true
        }
    }

    //MARK: UITABLEVIEW DELEGATE METHODS
    
    func numberOfSections(in tableView: UITableView) -> Int {
        if Int(selectedExTypeId) == 14 {
            return 2
        } else {
            return 1
        }
    }

    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        
        if selectedExTypeId == "14" {
            if section == 0 {
                return lastIndex
            } else {
                return tempOptions.count
            }
        }
        else
        {
            return arrType13Questions[questionIndex]["list"].count
        }
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        if indexPath.section == 0 {
            
            if arrType13Questions[questionIndex]["list"][indexPath.row]["speaker"].intValue == 1 {
                let cellSpeaker1 = tblViewDialogue
                    .dequeueReusableCell(withIdentifier: "CellSpeaker1") as! CellSpeaker1
 
                if (arrType13Questions[questionIndex]["list"][indexPath.row]["audio_name"].stringValue.isEmpty)
                {
                    cellSpeaker1.btnAudio1.isHidden = true
                }
                else
                {
                    cellSpeaker1.btnAudio1.isHidden = false
                    cellSpeaker1.btnAudio1.tag = indexPath.row
                    cellSpeaker1.btnAudio1.addTarget(self, action:#selector(cellBtnAudioPressed(sender:)), for: .touchUpInside)
                }
                
                if selectedTargetLanguageId == "38" {
                    cellSpeaker1.lblSpeaker1.text = arrType13Questions[questionIndex]["list"][indexPath.row]["phrase"].stringValue.replacingOccurrences(of: "$", with: ".")
                } else {
                    cellSpeaker1.lblSpeaker1.text = arrType13Questions[questionIndex]["list"][indexPath.row]["phrase"].stringValue
                }
     
                return cellSpeaker1
            }
            else
            {
                let cellSpeaker2 = tblViewDialogue
                    .dequeueReusableCell(withIdentifier: "CellSpeaker2") as! CellSpeaker2
                
                if (arrType13Questions[questionIndex]["list"][indexPath.row]["audio_name"].stringValue.isEmpty)
                {
                    cellSpeaker2.btnAudio2.isHidden = true
                }
                else
                {
                    cellSpeaker2.btnAudio2.isHidden = false
                    cellSpeaker2.btnAudio2.tag = indexPath.row
                    cellSpeaker2.btnAudio2.addTarget(self, action:#selector(cellBtnAudioPressed(sender:)), for: .touchUpInside)
                }
                
                if selectedTargetLanguageId == "38" {
                    cellSpeaker2.lblSpeaker2.text = arrType13Questions[questionIndex]["list"][indexPath.row]["phrase"].stringValue.replacingOccurrences(of: "$", with: ".")
                } else {
                    cellSpeaker2.lblSpeaker2.text = arrType13Questions[questionIndex]["list"][indexPath.row]["phrase"].stringValue
                }
                
                return cellSpeaker2
            }
        }
        else
        {
            let cellOptions = tblViewDialogue.dequeueReusableCell(withIdentifier: "cellExType1") as! CellExType1
            
            cellOptions.optionView.state = .normal

            if selectedTargetLanguageId == "38" {
                cellOptions.lblOption.text = tempOptions[indexPath.row]["phrase"].stringValue.replacingOccurrences(of: "$", with: ".")
            } else {
                cellOptions.lblOption.text = tempOptions[indexPath.row]["phrase"].stringValue
            }
            
            return cellOptions
        }
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        if indexPath.section == 1 {
            
            attempts += 1
            
            let cellOptions = tblViewDialogue.cellForRow(at: indexPath) as! CellExType1
            
            if tempOptions[indexPath.row]["sequence_no"].intValue == (lastIndex + 1) {
                lastIndex += 1
                
                if lastIndex < arrType13Questions[questionIndex]["list"].count {
                    createOptionsArray()
                }
                
                TapticEngine.notification.feedback(.success)
                
                if attempts == 1 {
                    score += 1
                }
                
                attempts = 0
                
                self.view.isUserInteractionEnabled = false
                
                UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                        
                    cellOptions.optionView.state = .green
                        
                },  completion: { (Bool) -> Void in
                    
                    DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                        UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                            cellOptions.optionView.state = .normal
                        })
                    }
                })
                
                UIView.transition(with: btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                    self.btnChooseOption.backgroundColor = Colors.green
                    self.btnChooseOption.setTitleColor(Colors.white, for: .normal)
                    self.btnChooseOption.setTitle("correct".localized(), for: .normal)
                }, completion: { _ in
                        
                    DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                        
                        if self.lastIndex == self.arrType13Questions[self.questionIndex]["list"].count
                        {
                            self.tblViewDialogue.reloadData()
                            
                            UIView.animate(withDuration: 0.3, animations: {
                                self.btnContinue.alpha = 1
                                self.btnContinue.isHidden = false
                                
                                self.btnChooseOption.alpha = 0
                                self.btnChooseOption.isHidden = true
                                
                                self.svButtons.layoutMargins = UIEdgeInsets(top: 15, left: 0, bottom: 20, right: 0)
                            }, completion: { _ in
                                self.scrView.scrollToBottom()
                                self.view.isUserInteractionEnabled = true
                            })
                        }
                        else
                        {
                            self.tblViewDialogue.reloadData()
                            
                            UIView.transition(with: self.btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                                self.btnChooseOption.backgroundColor = Colors.white
                                self.btnChooseOption.setTitleColor(Colors.black, for: .normal)
                                self.btnChooseOption.setTitle("chooseOption".localized(), for: .normal)
                            }, completion: { _ in
                                self.scrView.scrollToBottom()
                                self.view.isUserInteractionEnabled = true
                            })
                        }
                    }
                })
            }
            else
            {
                
                TapticEngine.notification.feedback(.error)
                
                cellOptions.shake()
                
                view.isUserInteractionEnabled = false
                
                UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                        
                    cellOptions.optionView.state = .red
                        
                },  completion: { (Bool) -> Void in
                    
                    DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                        UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                            cellOptions.optionView.state = .normal
                        })
                    }
                })
                
                
                UIView.transition(with: self.btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                    self.btnChooseOption.backgroundColor = Colors.white
                    self.btnChooseOption.setTitle("retry".localized(), for: .normal)
                    self.btnChooseOption.setTitleColor(Colors.red, for: .normal)
                }, completion: { _ in
                    
                    DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                        UIView.transition(with: self.btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                            self.btnChooseOption.backgroundColor = Colors.white
                            self.btnChooseOption.setTitle("chooseOption".localized(), for: .normal)
                            self.btnChooseOption.setTitleColor(Colors.black, for: .normal)
                        }, completion: { _ in
                            self.view.isUserInteractionEnabled = true
                        })
                    }
                })
            }
        }
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        UITableViewAutomaticDimension
    }
    
    func tableView(_ tableView: UITableView, heightForHeaderInSection section: Int) -> CGFloat {
        if section == 0 {
            return 0
        } else {
            return 30
        }
    }

    func tableView(_ tableView: UITableView, viewForHeaderInSection section: Int) -> UIView? {
        if section == 0 {
            return nil
        } else {
            return UIView()
        }
    }
    
    //MARK: BUTTON ACTIONS
    
    @IBAction func btnAudioClicked(_ sender: ThemeButton) {
        let url =  URL(string: urlString as String)
        
        if (url != nil) {
            
            if audioPlayer != nil {
                audioPlayer.removeObserver(self, forKeyPath: "status")
            }
            
            playerItem = AVPlayerItem(url: url!)
            
            indicator = UIActivityIndicatorView(activityIndicatorStyle: .whiteLarge)
            indicator.center = sender.center
            sender.superview?.addSubview(indicator)
            
            DispatchQueue.main.asyncAfter(deadline: .now() + 0.0)  {
                self.btnAudio.isUserInteractionEnabled = false
                self.indicator.startAnimating()
            }
            
            audioPlayer = AVPlayer(playerItem:playerItem)
            audioPlayer.addObserver( self, forKeyPath:"status", options:.initial, context:nil)
            
            audioPlayer.play()
        }
        else
        {
            SnackBar.show("Audio error")
        }
    }
    
    
    @IBAction func btnContinueAction(_ sender: ThemeButton) {
        
        if (score + 3) == arrType13Questions[questionIndex]["list"].count {
            finalScore += 1
        }
        
        self.questionIndex += 1
        
        if self.arrType13Questions.count > self.questionIndex
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
            
            if selectedExTypeId == "14" {
                objExComplete.score = finalScore
            }
            
            objExComplete.totalQuestions = arrType13Questions.count
            self.navigationController?.pushViewController(objExComplete, animated: true)
        }
    }
    
    @IBAction func btnChooseOptionAction(_ sender: UIButton) {
        
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}


extension UIScrollView {

    func scrollToBottom() {
        let point = CGPoint(x: 0, y: contentSize.height + contentInset.bottom - frame.height)
        if point.y >= 0 {
            setContentOffset(point, animated: true)
        }
    }
}
